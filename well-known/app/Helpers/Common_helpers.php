<?php

//die(__FILE__.' '.__LINE__);
if (!function_exists('loggedin_admin')) {
    function loggedin_admin($key = '')
    {
        if (!empty(Auth::User())) {
            $admin_user['id'] = Auth::user()->id;
            $admin_user['name'] = Auth::user()->name;
            $admin_user['email'] = Auth::user()->email;
            $admin_user['role'] = Auth::user()->roles[0]->id;
            $admin_user['role_name'] = Auth::user()->roles[0]->name;
            $admin_user['related_module'] = Auth::user()->related_module;
            $admin_user['module_id'] = Auth::user()->module_id;
        }
        if ($key != '')
            return !empty($admin_user[$key]) ? $admin_user[$key] : '';
        else
            return $admin_user;
    }
}
if (!function_exists('get_userrole')) {
    function get_userrole($id)
    {
        $data = DB::table('users')->select('users.*', 'roles.id as role_id', 'roles.name as role_name')->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->join('roles', 'roles.id', '=', 'model_has_roles.role_id')->where('users.id', $id)->get();
        return !empty($data[0]->role_name) ? $data[0]->role_name : '';
    }
}
if (!function_exists('get_username')) {
    function get_username($id)
    {
        $data = DB::table('users')->select('users.*', 'roles.id as role_id', 'roles.name as role_name')->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')->join('roles', 'roles.id', '=', 'model_has_roles.role_id')->where('users.id', $id)->get();
        return !empty($data[0]->name) ? $data[0]->name : '';
    }
}
if (!function_exists('get_ticket_history_list')) {
    function get_ticket_history_list($ticketid)
    {
        $data = DB::table('ticket_remarks_history')->where('ticket_remarks_history.ticket_id', $ticketid)->where('ticket_remarks_history.deleted', 0)->get();
        return $data;
    }
}
if (!function_exists('save_ticket_history_remarks')) {
    function save_ticket_history_remarks($ticketid, $remark)
    {
        $logged_user_id = loggedin_admin('id');
        if (!empty($ticketid) && !empty($remark)) {
            $insert_array['remarks'] = $remark;
            $insert_array['ticket_id'] = $ticketid;
            $insert_array['created_by'] = $logged_user_id;
            $insert_array['created_at'] = current_datetime();
            DB::table('ticket_remarks_history')->insert($insert_array);
            DB::table('requsition')->where('id', $ticketid)->update(array('remarks_update_date' => current_datetime()));
            add_history_remarks_read($ticketid);
            return true;
        }
    }
}
if (!function_exists('is_history_remarks_read')) {
    function is_history_remarks_read($ticketid)
    {
        $logged_user_id = loggedin_admin('id');
        if (!empty($ticketid)) {
            //$get_remarks_history = DB::table('remarks_read_flag')->select('remarks_read_flag.*')->join('ticket_remarks_history', 'ticket_remarks_history.id', '=', 'remarks_read_flag.remarks_history_id')->where('remarks_read_flag.read_by',$logged_user_id)->where('ticket_remarks_history.ticket_id',$ticketid)->orderBy('ticket_remarks_history.id', 'DESC')->first();->whereDate('ticket_remarks_history.created_at', '>', '2023-04-19')
            $get_remarks_history = DB::table('ticket_remarks_history')->select('ticket_remarks_history.*', DB::raw('(SELECT id FROM remarks_read_flag WHERE remarks_read_flag.remarks_history_id=ticket_remarks_history.id and remarks_read_flag.read_by =' . $logged_user_id . ' limit 1) as is_read'))->where('ticket_remarks_history.ticket_id', $ticketid)->orderBy('ticket_remarks_history.id', 'DESC')->first();
            //echo "<pre>";print_r($get_remarks_history);
            $data['is_read'] = !empty($get_remarks_history->is_read) ? 1 : 0;
            $data['created_at'] = !empty($get_remarks_history->created_at) ? $get_remarks_history->created_at : '';
            return $data;
        }
    }
}
if (!function_exists('add_history_remarks_read')) {
    function add_history_remarks_read($ticketid)
    {
        $logged_user_id = loggedin_admin('id');
        if (!empty($ticketid)) {
            $data_read = DB::table('ticket_remarks_history')->where('ticket_remarks_history.ticket_id', $ticketid)->where('ticket_remarks_history.deleted', 0)->orderBy('ticket_remarks_history.id', 'desc')->first();
            //echo "<pre>";print_r($data_read);die;
            if (!empty($data_read->id)) {
                $insert_array['remarks_history_id'] = $data_read->id;
                $insert_array['read_by'] = $logged_user_id;
                $insert_array['read_flag'] = 1;
                $insert_array['date'] = current_datetime();
                DB::table('remarks_read_flag')->insert($insert_array);
            }
        }
    }
}
if (!function_exists('current_datetime')) {
    function current_datetime()
    {
        return date('Y-m-d H:i:s');
    }
}
if (!function_exists('datetime_for_show')) {
    function datetime_for_show($date)
    {
        if ($date != '' && $date != "0000-00-00 00:00:00") {
            $datetime = new DateTime($date);
            $datetime->format('Y-m-d H:i:s') . "\n";
            $la_time = new DateTimeZone('Asia/Kolkata');
            $datetime->setTimezone($la_time);
            //return $datetime->format('Y-m-d H:i:s');
            return $datetime->format('d-m-Y h:i a');
        }
    }
}
if (!function_exists('date_for_show')) {
    function date_for_show($date)
    {
        if ($date != '' && $date != "0000-00-00 00:00:00") {
            $datetime = new DateTime($date);
            $datetime->format('Y-m-d H:i:s') . "\n";
            $la_time = new DateTimeZone('Asia/Kolkata');
            $datetime->setTimezone($la_time);
            //return $datetime->format('Y-m-d H:i:s');
            return $datetime->format('d-m-Y');
        }
    }
}
if (!function_exists('get_price_format')) {
    function get_price_format($price)
    {
        return number_format($price, 2);
    }
}
if (!function_exists('assign_rand_value')) {
    function assign_rand_value($num)
    {
        // accepts 1 - 36
        switch ($num) {
            case "1":
                $rand_value = "a";
                break;
            case "2":
                $rand_value = "b";
                break;
            case "3":
                $rand_value = "c";
                break;
            case "4":
                $rand_value = "d";
                break;
            case "5":
                $rand_value = "e";
                break;
            case "6":
                $rand_value = "f";
                break;
            case "7":
                $rand_value = "g";
                break;
            case "8":
                $rand_value = "h";
                break;
            case "9":
                $rand_value = "i";
                break;
            case "10":
                $rand_value = "j";
                break;
            case "11":
                $rand_value = "k";
                break;
            case "12":
                $rand_value = "l";
                break;
            case "13":
                $rand_value = "m";
                break;
            case "14":
                $rand_value = "n";
                break;
            case "15":
                $rand_value = "o";
                break;
            case "16":
                $rand_value = "p";
                break;
            case "17":
                $rand_value = "q";
                break;
            case "18":
                $rand_value = "r";
                break;
            case "19":
                $rand_value = "s";
                break;
            case "20":
                $rand_value = "t";
                break;
            case "21":
                $rand_value = "u";
                break;
            case "22":
                $rand_value = "v";
                break;
            case "23":
                $rand_value = "w";
                break;
            case "24":
                $rand_value = "x";
                break;
            case "25":
                $rand_value = "y";
                break;
            case "26":
                $rand_value = "z";
                break;
            case "27":
                $rand_value = "0";
                break;
            case "28":
                $rand_value = "1";
                break;
            case "29":
                $rand_value = "2";
                break;
            case "30":
                $rand_value = "3";
                break;
            case "31":
                $rand_value = "4";
                break;
            case "32":
                $rand_value = "5";
                break;
            case "33":
                $rand_value = "6";
                break;
            case "34":
                $rand_value = "7";
                break;
            case "35":
                $rand_value = "8";
                break;
            case "36":
                $rand_value = "9";
                break;
        }
        return $rand_value;
    }
}
if (!function_exists('get_rand_alphanumeric')) {
    function get_rand_alphanumeric($length, $letter_type = 's')
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float)microtime() * 1000000);
                $num = mt_rand(1, 36);
                $rand_id .= assign_rand_value($num);
            }
            $rand_id = ($letter_type == 'c') ? strtoupper($rand_id) : $rand_id;
        }
        return $rand_id;
    }
}
if (!function_exists('get_rand_numbers')) {
    function get_rand_numbers($length)
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float)microtime() * 1000000);
                $num = mt_rand(27, 36);
                $rand_id .= assign_rand_value($num);
            }
        }
        return $rand_id;
    }
}
if (!function_exists('get_rand_letters')) {
    function get_rand_letters($length)
    {
        if ($length > 0) {
            $rand_id = "";
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((float)microtime() * 1000000);
                $num = mt_rand(1, 26);
                $rand_id .= assign_rand_value($num);
            }
        }
        return $rand_id;
    }
}
