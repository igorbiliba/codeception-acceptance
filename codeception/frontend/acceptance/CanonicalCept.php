<?php
/**
 * тестит наличие канонических ссылок
 * берет резнотипные ссылки из сайтмепа
 */
use \tests\codeception\frontend\_helpers\SitemapUrlsGet;

$sitemap = new SitemapUrlsGet([
    'sitemapUrl' => SitemapUrlsGet::SITEMAP,
]);
$sitemap->loadSitemap();
$sitemap->eachElems();

foreach ($sitemap->urls as $url) {
    $I = new \tests\codeception\frontend\AcceptanceTester($scenario);
    $I->amOnUrl($url);
    $I->seeElement('link[rel=canonical]');
}