<?php

namespace donForms\donForms;

class User extends \donForms\donForms
{
    public function getList() {
        return $this->client->get($this->api_endpoint . '/users');
    }
    
    public function get($username = null) {
        return $this->client->get($this->api_endpoint . '/user/' . ($username?:''));
    }
    
    public function update($params, $username = null) {
        return $this->client->put($this->api_endpoint . '/user/' . ($username?:''), $params);
    }
    
    public function delete($username = null) {
        return $this->client->delete($this->api_endpoint . '/user/' . ($username?:''));
    }
}