<?php
class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(TRUE);
    }

    public function index()
    {
        redirect("/system/user");
    }
}
