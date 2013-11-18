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
        $relations = $contentService->loadReverseRelations( $contentService->loadContentInfo( $location->contentId ) );

        $userConferencesInfo = array();
        foreach ( $relations as $relation )
        {
            $userConferencesInfo[] = $relation->getSourceContentInfo();
        }

        return $this->get( 'ez_content' )->viewLocation(
            $locationId,
            $viewType,
            $layout,
            array( 'userConferences' => $userConferencesInfo ) + $params
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

    public function showLineConferenceAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        return $this->get( 'ez_content' )->viewLocation(
            $locationId,
            $viewType,
            $layout,
            array( 'room' => $this->getRoom( $locationId ) ) + $params
        );
    }

    public function showProgramAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        $searchService = $this->getRepository()->getSearchService();
        $query = new Query();

        $query->criterion = new Criterion\ContentTypeIdentifier( 'conference' );
        $query->sortClauses = array(
            new SortClause\Field( 'conference', 'debut' )
        );
        $results = $searchService->findContent( $query );
        $conferences = array();
        foreach ( $results->searchHits as $hit )
        {
            $conferences[] = $hit->valueObject;
        }

        return $this->get( 'ez_content' )->viewLocation(
            $locationId,
            $viewType,
            $layout,
            array( 'conferences' => $conferences ) + $params
        );
    }

    public function showSpeakersAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        $searchService = $this->getRepository()->getSearchService();
        $query = new Query();

        $query->criterion = new Criterion\ContentTypeIdentifier( 'conferencier' );
        $query->sortClauses = array(
            new SortClause\ContentName()
        );
        $results = $searchService->findContent( $query );
        $speakers = array();
        foreach ( $results->searchHits as $hit )
        {
            $speakers[] = $hit->valueObject;
        }
        return $this->get( 'ez_content' )->viewLocation(
            $locationId,
            $viewType,
            $layout,
            array( 'speakers' => $speakers ) + $params
        );
    }
}
