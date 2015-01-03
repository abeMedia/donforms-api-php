<?php

namespace donForms\donForms;

class Form extends \donForms\donForms
{
    public function getList($username = null) {
        return $this->client->get($this->api_endpoint . '/forms/' . ($username?:''));
    }
    
    public function get($form) {
        return $this->client->get($this->api_endpoint . '/form/' . $form);
    }
    
    public function update($form, $params) {
        return $this->client->put($this->api_endpoint . '/form/' . $form, $params);
    }
    
    public function delete($form) {
        return $this->client->delete($this->api_endpoint . '/form/' . $form);
    }
}
