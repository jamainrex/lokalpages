<?php

/**
 * Send Invoice email
 */
function lp_send_invoice_email( $customer_name, $customer_mobile, $customer_email, $postId )
{
    $post = get_post( $postId );
    $to = $customer_email;
    //$headers[] = 'From: '. $_POST['name'] .' <' . $_POST['from'] . '>';

    if( trim( $_POST['to'] ) != 'kitprimor@gmail.com' )
        $headers[] = 'Bcc: <kitprimor@gmail.com >';

    $headers[] = 'Bcc: <skyguyverph@gmail.com >';
    $headers[] = 'Bcc: <alryan.santiago@lokalpages.com >';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $result = wp_mail( strip_tags($to), "Lokalpages-Invoice - ".$post->post_title, lp_invoice_email_bodyTemplate( strip_tags( $customer_name ), strip_tags( $customer_mobile ) ), $headers );
    if (!$result) {
        _e( 'Error with sending email', 'ait' );
        exit();
    }
}

function lp_invoice_email_bodyTemplate( $customer_name, $customer_mobile )
{
    ob_start();
    $invoice_date = date("F j, Y");
    require dirname(__FILE__) . '/invoice_email_template.html';
    $email_body = ob_get_contents();
    ob_end_clean();

    return (String) $email_body;
}