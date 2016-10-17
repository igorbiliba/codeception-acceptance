<?php
namespace tests\codeception\frontend\_helpers;


use yii\base\Component;

/**
 * вернет несколько ссылок из сайтмепа для чека
 * каноникал
 *
 * Class SitemapUrlsGet
 * @package tests\codeception\frontend\_helpers
 */
class SitemapUrlsGet extends Component
{
    const SITEMAP = 'http://www.b2b.by/sitemap.xml';

    /**
     * три ссылки из каждого раздела
     */
    const COUNT_URL_BY_TYPE = 3;

    public $sitemapUrl = null;

    /**
     * части сайтмапа
     * @var array
     */
    private $sitemaps = [];

    /**
     * ссылки для теста
     *
     * @var array
     */
    public $urls = [];

    /**
     * загрузить основной сайтмеп
     */
    public function loadSitemap() {
        $content = $this->getXmlFile($this->sitemapUrl);
        $elems = $this->parseXmlFile($content);
        $sitemapsAll = $this->getUrlByArray($elems);
        $sitemapsUnique = [];
        //уникальные
        foreach ($sitemapsAll as $url) {
            if(!preg_match('/(\d\.xml)+/i', $url))
                $sitemapsUnique[$url] = $url;
        }

        $this->sitemaps = $sitemapsUnique;
    }

    protected function getUrlByArray($elems, $tag = 'loc') {
        $urls = [];
        foreach ($elems as $elem) {
            if(isset($elem['tag']) && strtolower($elem['tag']) == strtolower($tag)) {
                if(isset($elem['value'])) {
                    $urls[] = $elem['value'];
                }
            }
        }

        return $urls;
    }

    /**
     * перебрать сайтмепы из сайтмепа)
     */
    public function eachElems() {
        $urls = [];
        foreach ($this->sitemaps as $url) {
            $content = $this->getXmlFile($url);
            $elems = $this->parseXmlFile($content);
            $urls[$url] = $this->getUrlByArray($elems);
        }
        $this->replacementUrlToFull($urls);
    }

    /**
     * @param $urls
     */
    protected function replacementUrlToFull($urls) {
        foreach ($urls as $items) {
            $scope = array_splice($items, 0, self::COUNT_URL_BY_TYPE);
            if(is_array($scope)) {
                foreach ($scope as $i) {
                    $this->urls[] = $i;
                }
            }
        }
    }

    /**
     * получит контент по ссылке
     *
     * @param $url
     */
    protected function getXmlFile($url) {
        return file_get_contents($url);
    }

    /**
     * парсим xml в зависимости от типа документа
     *
     * @param $xml
     */
    protected function parseXmlFile($xml) {
        $p = xml_parser_create();
        $vals = null;
        $index = null;
        xml_parse_into_struct($p, $xml, $vals, $index);
        xml_parser_free($p);

        return $vals;
    }
}