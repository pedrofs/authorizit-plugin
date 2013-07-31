<?php
use Authorizit\ModelAdapter\ModelAdapterInterface;

class CakeModelAdapter implements ModelAdapterInterface
{
    protected $loader = array();

    public function loadResources($rules)
    {
        $this->setJoins($rules);
        $this->setConditions($rules);

        return $this->loader;
    }

    private function setJoins($rules)
    {
        $this->loader['joins'] = array();
    }

    private function setConditions($rules)
    {
        $this->loader['conditions'] = array();

        foreach ($rules as $rule) {
            $ruleConditions = $rule->getConditions();

            if (empty($ruleConditions)) {
                $this->loader['conditions'] = array();
                return;
            }

            $this->loader['conditions'] = array_merge(
                $this->loader['conditions'],
                $ruleConditions
            );
        }
    }
}