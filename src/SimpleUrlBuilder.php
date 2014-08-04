<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\Url;


class SimpleUrlBuilder extends BaseUrlBuilder {
    private $routeParameterName = 'r';
    private $baseUrl = '/';

    /**
     * @return string
     */
    public function getRouteParameterName() {
        return $this->routeParameterName;
    }

    /**
     * @param string $routeParameterName
     * @return $this
     */
    public function setRouteParameterName($routeParameterName) {
        $this->routeParameterName = $routeParameterName;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    protected function createUrl() {
        return sprintf('%s?%s',
            $this->getBaseUrl(),
            $this->getParamsToString()
        );
    }

    protected function getParamsToString() {
        return http_build_query(array_merge(array(
            $this->getRouteParameterName() => $this->getRoute(),
        ), $this->getParams()));
    }
}
 