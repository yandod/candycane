<?php
/**
 * SortComponent
 *
 */
class SortComponent extends Component
{
    public $components = array('Session');
    public $sort_name = null;
    public $sort_default = null;

    /**
     * startUp
     */
    public function startup(Controller $controller)
    {
        $this->controller = $controller;
        $this->request = $controller->request;
    }

    /**
     * sort_init
     *
     * Initializes the default sort column (default_key) and sort order
     * (default_order).
     *
     * - default_key is a column attribute name.
     * - default_order is 'asc' or 'desc'.
     * - name is the name of the session hash entry that stores the sort state,
     *   defaults to '<controller_name>_sort'.
     *
     */
    public function sort_init($default_key, $default_order = 'asc', $name = null)
    {
        if ($name != null) {
            $this->sort_name = $name;
        } else {
            $this->sort_name = $this->request->params['controller'] . $this->request->params['action'] . '_sort';
        }

        $this->sort_default = array('key' => $default_key, 'order' => $default_order);
    }

    /**
     * sort_update
     *
     * Updates the sort state. Call this in the controller prior to calling
     * sort_clause.
     * sort_keys can be either an array or a hash of allowed keys
     */
    public function sort_update($sort_keys)
    {
        $r_sort_key = null;

        if (isset($this->request->params['url']['sort_key'])) {
            $r_sort_key = $this->request->params['url']['sort_key'];
        }
        if (isset($this->controller->params['named']['sort'])) {
            $r_sort_key = $this->controller->params['named']['sort'];
        }

        $sort_key = null;

        if (is_array($sort_keys) && in_array($r_sort_key, $sort_keys)) {
            $sort_key = $r_sort_key;
        }

        if (isset($this->request->params['url']['sort_order'])) {
            if (strtolower($this->request->params['url']['sort_order']) == 'desc') {
                $sort_order = 'DESC';
            } else {
                $sort_order = 'ASC';
            }
        } elseif (isset($this->controller->params['named']['direction'])) {
            if (strtolower($this->controller->params['named']['direction']) == 'desc') {
                $sort_order = 'DESC';
            } else {
                $sort_order = 'ASC';
            }
        } else {
            $sort_order = 'ASC';
        }
        if ($sort_key != null) {
            $sort = array('key' => $sort_key, 'order' => $sort_order);
        } else {
            if ($this->Session->read($this->sort_name)) {
                $sort = $this->Session->read($this->sort_name); // Previous sort.
            } else {
                $sort = $this->sort_default;
            }
        }

        $this->Session->write($this->sort_name, $sort);

        $sort_column = null;

        if (isset($sort['key'])) {
            $sort_column = $sort['key'];
        }

        if (is_array($sort_keys)) {
            $sort_column = $sort['key'];
        }

        $this->sort_clause = null;
        if (!empty($sort_column)) {
            $this->sort_clause = "{$sort_column} {$sort['order']}";
        }
        $this->controller->request->params['named']['sort'] = $sort_column;
        $this->controller->request->params['named']['direction'] = strtolower($sort['order']);
        return $this->sort_clause;
    }

    /**
     * sort_clause
     *
     * Returns an SQL sort clause corresponding to the current sort state.
     * Use this to sort the controller's table items collection.
     */
    public function sort_clause()
    {
        return $this->sort_clause;
    }

}


