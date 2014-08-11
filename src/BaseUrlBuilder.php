<?php
/**
 * @author Petr Grishin <petr.grishin@grishini.ru>
 */

namespace PetrGrishin\Url;


use PetrGrishin\Url\Exception\UrlBuilderException;

abstract class BaseUrlBuilder {
    const PARAMETER_NAME_HASH = '#';

    private $params = array();
    /** @var array */
    private $required = array();

    public static function className() {
        return get_called_class();
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception\UrlBuilderException
     */
    public function getParam($name) {
        if (!array_key_exists($name, $this->params)) {
            throw new Exception\UrlBuilderException(sprintf('This param `%s` not exists'));
        }
        return $this->params[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam($name, $value) {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * @return string
     * @throws Exception\UrlBuilderException
     */
    public function getHash() {
        if (!array_key_exists(self::PARAMETER_NAME_HASH, $this->params)) {
            return null;
        }
        return $this->getParam(self::PARAMETER_NAME_HASH);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setHash($value) {
        $this->setParam(self::PARAMETER_NAME_HASH, $value);
        return $this;
    }

    /**
     * @return array
     */
    public function getRequired() {
        return $this->required;
    }

    /**
     * @param array $required
     * @return $this
     */
    public function setRequired(array $required) {
        $this->required = $required;
        return $this;
    }

    /**
     * @throws Exception\UrlBuilderException
     * @return string
     */
    public function getUrl() {
        if ($this->hasRequiredParams()) {
            throw new UrlBuilderException(sprintf('Required params `%s` not exists', implode(', ', $this->requiredParams())));
        }
        return $this->createUrl();
    }

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @return BaseUrlBuilder
     */
    public function copy() {
        return clone $this;
    }

    abstract protected function createUrl();

    protected function hasRequiredParams() {
        return (boolean)$this->requiredParams();
    }

    protected function requiredParams() {
        return array_diff($this->required, array_keys(array_filter($this->params)));
    }
}
 
