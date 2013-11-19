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
/*
use eZ\Publish\API\Repository\Values\Content\Location;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
*/

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
}
