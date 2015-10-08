<?php
/**
 * Authorizit Cake Plugin - Cake plugin for using Authorizit. See: https://github.com/pedrofs/authorizit
 * https://github.com/pedrofs/authorizit-plugin
 *
 * Licensed under UNLICENSE
 * For full copyright and license information, please see the UNLICENSE.txt
 * Check http://unlicense.org/
 *
 * @link          https://github.com/pedrofs/authorizit-plugin
 * @license       http://unlicense.org/ Unlicense Yourself: Set Your Code Free
 */

use Authorizit\ModelAdapter\ModelAdapterInterface;

/**
 * This class is the adapter to enable authorizit to load resources from database
 */
class CakeModelAdapter implements ModelAdapterInterface
{
    /**
     * The $loader is the array used to find in CakePHP
     *
     * @var array $loader
     */
    protected $loader = array();

    /**
     * @param array $rules
     * @return array The resulting array to find resources in the database
     */
    public function loadResources($rules)
    {
        $this->setJoins($rules);
        $this->setConditions($rules);

        return $this->loader;
    }

    /**
     * Not really implemented yet
     *
     * @param array $rules
     */
    private function setJoins($rules)
    {
        $this->loader['joins'] = array();
    }

    /**
     * @param array $rules
     */
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