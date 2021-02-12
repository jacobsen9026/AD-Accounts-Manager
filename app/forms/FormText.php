<?php


namespace App\Forms;


use App\Models\View\Javascript;

class FormText extends \System\App\Forms\FormText
{

    protected const DOMAIN_API_URL = '/api/domain';

    /**
     * Autocomplete for users
     *
     * @return self
     */
    public function autoCompleteUsername()
    {
        $this->autocomplete = true;
        $script = Javascript::buildAutocomplete(self::DOMAIN_API_URL . '/autocompleteUser/', $this->getId());
        $this->setScript($script);
        return $this;
    }

    /**
     * Autocomplete for ous
     *
     * @return $this
     */
    public function autoCompleteOU()
    {
        $this->autocomplete = true;
        $script = Javascript::buildAutocomplete(self::DOMAIN_API_URL . '/autocompleteOU/', $this->getId());
        $this->setScript($script);
        return $this;
    }

    /**
     * Autocomplete for groups
     *
     * @return \System\App\Forms\FormText
     */
    public function autoCompleteGroupName()
    {
        $this->autocomplete = true;
        $script = Javascript::buildAutocomplete(self::DOMAIN_API_URL . '/autocompleteGroup/', $this->getId());
        $this->setScript($script);
        return $this;
    }

    /**
     * Autocomplete for all users in domain (permissions apply)
     *
     * @return \System\App\Forms\FormText
     */
    public function autoCompleteDomainUsername()
    {
        $this->autocomplete = true;
        $script = Javascript::buildAutocomplete(self::DOMAIN_API_URL . '/autocompleteDomainUser/', $this->getId());
        $this->setScript($script);
        return $this;
    }

    /**
     * Autocomplete for all groups in domain (permissions apply)
     *
     * @return $this
     */
    public function autoCompleteDomainGroupName()
    {
        $this->autocomplete = true;
        $script = Javascript::buildAutocomplete(self::DOMAIN_API_URL . '/autocompleteDomainGroup/', $this->getId());
        $this->setScript($script);
        return $this;
    }


    public function autoCompleteUsernameOrGroupName()
    {
        $this->autocomplete = true;
        $script = Javascript::buildAutocomplete(self::DOMAIN_API_URL . '/autocompleteUserOrGroup/', $this->getId());
        $this->setScript($script);
        return $this;
    }

}