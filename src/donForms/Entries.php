<?php

namespace donForms\donForms;

class Entries extends \donForms\donForms
{
    public function getList($form) {
        return $this->client->get($this->api_endpoint . 'entries/' . $form);
    }
    
    public function get($id) {
        return $this->client->get($this->api_endpoint . 'entry/' . $id);
    }
    
    public function update($id, $params) {
        return $this->client->put($this->api_endpoint . 'entry/' . $id, $params);
    }
    
    public function delete($id) {
        return $this->client->delete($this->api_endpoint . 'entry/' . $id);
    }
}