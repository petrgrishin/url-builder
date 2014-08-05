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
        return sprintf('%s?%s%s',
            $this->getBaseUrl(),
            $this->getParamsToString(),
            urlencode($this->getHash() ? '#' . $this->getHash() : null)
        );
    }

    protected function getParamsToString() {
        $params = $this->getParams();
        if (array_key_exists(self::PARAMETER_NAME_HASH, $params)) {
            unset($params[self::PARAMETER_NAME_HASH]);
        }
        return http_build_query(array_merge(array(
            $this->getRouteParameterName() => $this->getRoute(),
        ), $params));
    }
}
 