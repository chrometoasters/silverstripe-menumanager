<?php

/**
 * Class MenuItem
 */
class MenuItem extends Link implements PermissionProvider
{

    private static $db = array(
        'Sort'  => 'Int',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'MenuSet' => 'MenuSet' // parent MenuSet
    );

    /**
     * @var array
     */
    private static $searchable_fields = array(
        'Title',
    );

    /**
     * @var array
     */
    private static $summary_fields = array(
        'Title',
        'LinkType',
        'LinkURL',
        'OpenInNewWindow',
    );

    /**
     * @var string
     */
    private static $default_sort = 'Sort';

    /**
     * @return array
     */
    public function providePermissions()
    {
        return array(
            'MANAGE_MENU_ITEMS' => 'Manage Menu Items',
        );
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canCreate($member = null)
    {
        return Permission::check('MANAGE_MENU_ITEMS');
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return Permission::check('MANAGE_MENU_ITEMS');
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return Permission::check('MANAGE_MENU_ITEMS');
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check('MANAGE_MENU_ITEMS');
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(array(
            'Sort',
            'MenuSetID',
        ));

        return $fields;
    }

    /**
     * @return mixed
     */
    public function Parent()
    {
        return $this->MenuSet();
    }

    /**
     * Attempts to return the $field from this MenuItem
     * If $field is not found or it is not set then attempts
     * to return a similar field on the associated Page
     * (if there is one)
     *
     * @param string $field
     * @return mixed
     */
    public function __get($field)
    {
        $default = parent::__get($field);

        if (($default || $field === 'ID') && ($field !== 'ClassName')) {
            return $default;
        } else {
            $page = $this->SiteTree();

            if ($page instanceof DataObject) {
                if ($page->hasMethod($field)) {
                    return $page->$field();
                } else {
                    return $page->$field;
                }
            }

            $file = $this->File();

            if ($file instanceof DataObject) {
                if ($file->hasMethod($field)) {
                    return $file->$field();
                } else {
                    return $file->$field;
                }
            }
        }
    }

}
