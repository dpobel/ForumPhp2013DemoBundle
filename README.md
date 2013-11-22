Demo bundle for the *eZ Publish : un CMS pour créer un site orienté contenu en
45 minutes* given at the Forum PHP 2013.

More info in:

* [Conference at the Forum PHP 2013: Create an eZ Publish website in 45
  minutes](http://share.ez.no/blogs/damien-pobel/conference-at-the-forum-php-2013-create-an-ez-publish-website-in-45-minutes)
* [Forum PHP 2013: eZ Publish, créer un site orienté contenu en 45 min.](http://damien.pobel.fr/post/ez-publish-forum-php-2013-creer-site-oriente-contenu) (same in French)

# Install

In a recent install of eZ Publish:

* Clone this repository in `<ezpublish5>/src/EzSystems/` (create the folder if it
  does not exist)
* [Import the package](http://doc.ez.no/eZ-Publish/Technical-manual/4.x/Features/Packages/Importing-packages-to-the-system)
  available
in the `_install` directory
* Add the following line in `ezpublish/EzPublishKernel.php` at the end of
  `$bundles` array:
  ```php new
  EzSystems\ForumPhp2013DemoBundle\EzSystemsForumPhp2013DemoBundle(),
  ```
* Clear the caches with: `php ezpublish/console cache:clear`
