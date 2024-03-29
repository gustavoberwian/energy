<?php

namespace Ozdemir\Datatables;

use Ozdemir\Datatables\DB\DatabaseInterface;

class Datatables {

    protected $db;
    protected $data;
    protected $recordstotal;
    protected $recordsfiltered;
    protected $columns;
    protected $add;
    protected $edit;
    protected $hide;
    protected $sql;
    protected $query;
    protected $hasOrderIn;

    function __construct(DatabaseInterface $db)
    {
        $this->db = $db->connect();
        $this->input = isset($_POST["draw"]) ? $_POST : $_GET;
    }

    public function query($query)
    {
        $this->hasOrderIn = $this->isQueryWithOrderBy($query);
        $this->columns = $this->setcolumns($query);
        $columns = implode(", ", $this->columns);
        $query = rtrim($query, "; ");
        $this->sql = "Select $columns from ($query)t";

        return $this;
    }

    public function get($request)
    {
        switch ($request)
        {
            case 'columns':
                return array_values(array_diff($this->columns, (array) $this->hide));
                break;
            case 'all_columns':
                return $this->columns;
                break;
            case 'sql':
                return $this->query;
                break;
        }
    }

    public function hide($columns)
    {
        if ( ! is_array($columns))
        {
            $columns = func_get_args();
        }
        $columns = array_intersect($this->columns, $columns);
        $this->hide = array_merge((array) $this->hide, array_combine($columns, $columns));

        return $this;
    }

    protected function execute()
    {
        $this->recordstotal = $this->db->count($this->sql); // unfiltered data count is here.
        $where = $this->filter();
        $this->recordsfiltered = $this->db->count($this->sql . $where);  // filtered data count is here.
        $this->query = $this->sql . $where . $this->orderby() . $this->limit();

        $this->data = $this->db->query($this->query);

        return $this;
    }

    protected function filter()
    {
        $search = '';

        $filterglobal = $this->filterglobal();
        $filterindividual = $this->filterindividual();

        if ( ! $filterindividual && ! $filterglobal)
        {
            return null;
        }

        $search .= $filterglobal;

        if ($filterindividual <> null && $filterglobal <> null)
        {
            $search .= ' AND ';
        }

        $search .= $filterindividual;
        $search = " WHERE " . $search;

        return $search;
    }

    protected function filterglobal()
    {
        $searchinput = $this->input('search')['value'];
        $allcolumns = $this->input('columns');

        if ($searchinput == '')
        {
            return null;
        }

        $search = array();
        $searchinput = preg_replace("/[^\wá-žÁ-Ž]+/", " ", $searchinput);
        foreach (explode(' ', $searchinput) as $word)
        {
            $lookfor = array();
            foreach ($this->columns as $key => $column)
            {
				if(array_key_exists($key,$allcolumns)){
					if ($allcolumns[ $key ]['searchable'] == 'true')
						$lookfor[] = $column . " LIKE " . $this->db->escape($word) . "";
				}
            }
            $search[] = "(" . implode(" OR ", $lookfor) . ")";
        }

        return implode(" AND ", $search);
    }

    protected function filterindividual()
    {
        $allcolumns = $this->input('columns');

        $search = " (";
        $lookfor = array();

        if ( ! $allcolumns)
        {
            return null;
        }

        foreach ($allcolumns as $key)
        {
            if ($key['search']['value'] <> "" and $key['searchable'] == 'true')
            {
                $lookfor[] = $this->column($key['data']) . " LIKE " . $this->db->escape('%' . $key['search']['value'] . '%') . "";
            }
        }

        if (count($lookfor) > 0)
        {
            $search .= implode(" AND ", $lookfor) . ")";

            return $search;
        }

        return null;
    }

    protected function setcolumns($query)
    {
        $query = preg_replace("/\((?:[^()]+|(?R))*+\)/is", "", $query);
        preg_match_all("/SELECT([\s\S]*?)((\s*)\bFROM\b(?![\s\S]*\)))([\s\S]*?)/is", $query, $columns);

        $columns = $this->explode(",", $columns[1][0]);

        // gets alias of the table -> 'table.column as col' or 'table.column col' to 'col'
        $regex[] = "/(.*)\s+as\s+(.*)/is";
        $regex[] = "/.+(\([^()]+\))?\s+(.+)/is";
        // wipe unwanted characters => '`" and space
        $regex[] = '/[\s"\'`]+/';
        // if there is no alias, return column name -> table.column to column
        $regex[] = "/([\w\-]*)\.([\w\-]*)/";

        return preg_replace($regex, "$2", $columns);
    }

    protected function isQueryWithOrderBy($query)
    {
        return (bool) count(preg_grep("/(order\s+by)\s+(.+)$/i", explode("\n", $query)));
    }

    protected function limit()
    {
        $take = 10;
        $skip = (integer) $this->input('start');

        if ($this->input('length'))
        {
            $take = (integer) $this->input('length');
        }

        if ($take == - 1 || ! $this->input('draw'))
        {
            return null;
        }

        return " LIMIT $take OFFSET $skip";
    }

    protected function orderby()
    {
        $dtorders = $this->input('order');
        $orders = " ORDER BY ";
        $dir = ['asc' => 'asc', 'desc' => 'desc'];

        if ( ! is_array($dtorders))
        {
            if ($this->hasOrderIn)
            {
                return null;
            }

            return $orders . $this->columns[0] . " asc";  // default
        }

        foreach ($dtorders as $order)
        {
            $takeorders[] = $this->columns[ $order['column'] ] . " " . $dir[ $order['dir'] ];
        }

        return $orders . implode(",", $takeorders);
    }

    public function generate($json = true, $extra = array())
    {
        $this->execute();
        $formatted_data = array();

        foreach ($this->data as $key => $row)
        {
            // new columns..
            if (null !== $this->add && count($this->add) > 0)
            {
                foreach ($this->add as $new_column => $closure)
                {
                    $row[ $new_column ] = $closure($row);
                }
            }

            // editing columns..
            if (null !== $this->edit && count($this->edit) > 0)
            {
                foreach ($this->edit as $edit_column => $closure)
                {
                    if (isset($row[ $edit_column ])) {
                        $row[ $edit_column ] = $closure($row);
                    }
                }
            }

            // hide unwanted columns from output
            $row = array_diff_key($row, (array) $this->hide);

            $formatted_data[] = $this->isIndexed($row);
        }

        $response['draw'] = $this->input('draw');
        $response['recordsTotal'] = $this->recordstotal;
        $response['recordsFiltered'] = $this->recordsfiltered;
        $response['data'] = $formatted_data;

        if (count($extra))
            $response['extra'] = $extra;

        return $this->response($response, $json);
    }

    public function add($newColumn, $closure)
    {
        $this->add[ $newColumn ] =  $closure;

        return $this;
    }

    public function edit($column, $closure)
    {
        $this->edit[ $column ] = $closure;

        return $this;
    }

    public function input($input)
    {
        if (isset($this->input[ $input ]))
        {
            return $this->input[ $input ];
        }

        return false;
    }

    protected function column($input)
    {
        if (is_numeric($input))
        {
            return $this->columns[ $input ];
        }

        return $input;
    }

    protected function response($data, $json = true)
    {
        if ($json)
        {
            header('Content-type: application/json');

            return json_encode($data);
        }

        return $data;
    }

    protected function isIndexed($row) // if data source uses associative keys or index number
    {
        $column = $this->input('columns');
        if (is_numeric($column[0]['data']))
        {
            return array_values($row);
        }

        return $row;
    }

    protected function balanceChars($str, $open, $close)
    {
        $openCount = substr_count($str, $open);
        $closeCount = substr_count($str, $close);
        $retval = $openCount - $closeCount;

        return $retval;
    }

    protected function explode($delimiter, $str, $open = '(', $close = ')')
    {
        $retval = array();
        $hold = array();
        $balance = 0;
        $parts = explode($delimiter, $str);
        foreach ($parts as $part)
        {
            $hold[] = $part;
            $balance += $this->balanceChars($part, $open, $close);
            if ($balance < 1)
            {
                $retval[] = implode($delimiter, $hold);
                $hold = array();
                $balance = 0;
            }
        }
        if (count($hold) > 0)
        {
            $retval[] = implode($delimiter, $hold);
        }

        return $retval;
    }
}