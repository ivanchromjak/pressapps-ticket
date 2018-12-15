<?php

function pati_is_valid_category($category){
    
    if(empty($category))
        return FALSE;
    
    $category = get_term_by('id', $category, 'ticket_category');
    
    return empty($category)?FALSE:TRUE;
}

function pati_is_valid_status($status){
    
    if(empty($status))
        return FALSE;
    
    $status = get_term_by('id', $status, 'ticket_status');
    
    return empty($status)?FALSE:TRUE;
}

function pati_is_valid_priority($status){
    
    if(empty($status))
        return FALSE;
    
    $status = get_term_by('id', $status, 'ticket_priority');
    
    return empty($status)?FALSE:TRUE;
}

function pati_is_valid_type($status){
    
    if(empty($status))
        return FALSE;
    
    $status = get_term_by('id', $status, 'ticket_type');
    
    return empty($status)?FALSE:TRUE;
}

function pati_is_valid_visibility($visibility){
    if(empty($visibility))
        return FALSE;
    
    $ticket_visibility  = PATI()->ticket_visibility;
    unset($ticket_visibility['userdefine']);
    
    return array_key_exists($visibility ,$ticket_visibility);
}