<?php
/**
 * File containing the ForumPhpController class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace EzSystems\ForumPhp2013DemoBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

class ForumPhpController extends Controller
{

    public function showSpeakerAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        $locationService = $this->getRepository()->getLocationService();
        $contentService = $this->getRepository()->getContentService();

        $location = $locationService->loadLocation( $locationId );
        $contentInfo = $contentService->loadContentInfo( $location->contentId );
        $relations = $contentService->loadReverseRelations( $contentInfo );

        // expose the conferences made by the user
        // by using the reverse relation conference <- conferencier
        $params['userConferences'] = array();
        foreach ( $relations as $relation )
        {
            $params['userConferences'][] = $relation->getSourceContentInfo();
        }

        return $this->get( 'ez_content' )->viewLocation(
            $locationId, $viewType, $layout, $params
        );
    }

    protected function getRoom( $locationId )
    {
        $locationService = $this->getRepository()->getLocationService();
        $contentService = $this->getRepository()->getContentService();

        $location = $locationService->loadLocation( $locationId );
        $roomLocation = $locationService->loadLocation( $location->parentLocationId );
        return $contentService->loadContent( $roomLocation->contentId );
    }

    public function showConferenceAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        // expose the room in the template
        $params['room'] = $this->getRoom( $locationId );
        return $this->get( 'ez_content' )->viewLocation(
            $locationId, $viewType, $layout, $params
        );
    }

    public function showProgramAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        // search for all 'conference' contents
        // sorted by beginning date
        $searchService = $this->getRepository()->getSearchService();
        $query = new Query();

        $query->criterion = new Criterion\ContentTypeIdentifier( 'conference' );
        $query->sortClauses = array(
            new SortClause\Field( 'conference', 'debut' )
        );
        $results = $searchService->findContent( $query );

        // expose the list of conferences
        $params['conferences'] = array();
        foreach ( $results->searchHits as $hit )
        {
            $params['conferences'][] = $hit->valueObject;
        }

        return $this->get( 'ez_content' )->viewLocation(
            $locationId, $viewType, $layout, $params
        );
    }

    public function showSpeakersAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        // search for all 'conferencier' object
        // sorted by content name
        $searchService = $this->getRepository()->getSearchService();
        $query = new Query();

        $query->criterion = new Criterion\ContentTypeIdentifier( 'conferencier' );
        $query->sortClauses = array(
            new SortClause\ContentName()
        );
        $results = $searchService->findContent( $query );

        // expose the list of speakers
        $params['speakers'] = array();
        foreach ( $results->searchHits as $hit )
        {
            $params['speakers'][] = $hit->valueObject;
        }
        return $this->get( 'ez_content' )->viewLocation(
            $locationId, $viewType, $layout, $params
        );
    }
}
