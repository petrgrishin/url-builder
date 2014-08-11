<?php
use PetrGrishin\Url\SimpleUrlBuilder;

/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

class UrlBuilderTest extends PHPUnit_Framework_TestCase {

    /**
     * @throws PetrGrishin\Url\Exception\UrlBuilderException
     */
    public function testCreateUrlBuilder() {
        $route = 'site/index';
        $params = array('id' => 1);
        $urlBuilder = $this->createUrlBuilder($route, $params);
        $this->assertEquals($route, $urlBuilder->getRoute());
        $this->assertEquals($params, $urlBuilder->getParams());
        $this->assertEquals('/?r=site%2Findex&id=1', $urlBuilder->getUrl());
        $this->assertEquals(array(
            'route' => $route,
            'params' => $params,
            'required' => array(),
        ), $urlBuilder->toArray());
        $urlBuilder->setParam('id', 2);
        $this->assertEquals(2, $urlBuilder->getParam('id'));
        $hash = 'hash';
        $urlBuilder->setHash($hash);
        $this->assertEquals($hash, $urlBuilder->getHash());
        $this->assertEquals('/?r=site%2Findex&id=2%23hash', $urlBuilder->getUrl());
    }

    public function testRequiredParams() {
        $route = 'site/index';
        $params = array('id' => 1);
        $urlBuilder = $this->createUrlBuilder($route, $params)
            ->setRequired(array('id'));
        $this->assertEquals('/?r=site%2Findex&id=1', $urlBuilder->getUrl());
    }

    /**
     * @expectedException \PetrGrishin\Url\Exception\UrlBuilderException
     */
    public function testNotExistsRequiredParams() {
        $route = 'site/index';
        $params = array('id' => 1);
        $urlBuilder = $this->createUrlBuilder($route, $params)
            ->setRequired(array('required'));
        $this->assertEquals('/?r=site%2Findex&id=1', $urlBuilder->getUrl());
    }

    public function createUrlBuilder($route, $params = array()) {
        $urlBuilder = new SimpleUrlBuilder();
        $urlBuilder
            ->setRoute($route)
            ->setParams($params);
        return $urlBuilder;
    }
}
 