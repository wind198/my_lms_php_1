<?php
trait DefineRoutesOnController
{
    public array $routes;

    public string $INDEX_ROUTE;
    public string $SHOW_ROUTE;
    public string $CREATE_ROUTE;
    public string $EDIT_ROUTE;
    public string $STORE_ROUTE;
    public string $DESTROY_ROUTE;
    public string $DESTROY_MANY_ROUTE;

    protected function defineRoutes(string $indexRoute)
    {
        $this->INDEX_ROUTE = $indexRoute;
        $this->STORE_ROUTE = $indexRoute . '.store';
        $this->SHOW_ROUTE = $indexRoute . '.show';
        $this->EDIT_ROUTE = $indexRoute . '.edit';
        $this->UPDATE_ROUTE = $indexRoute . '.update';
        $this->DELETE_ROUTE = $indexRoute . '.delete';
    }
}

