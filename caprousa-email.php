<?php
/** 
* Plugin Name: Caprousa Email
* Description: Caprousa Email for sending email
* Version: 1.0
* Author: Ayooluwa Odutayo 
* URL: http://budelak.com
*/

/**
 * 
 */
class Caprousa_Email
{
  
  public function load() {
    # code...
    require_once ABSPATH . '/wp-content/plugins/donation-button/includes/class-donation-button-paypal-listner.php';

    $Donation_Button_PayPal_listner = new Donation_Button_PayPal_listner();
    if ($Donation_Button_PayPal_listner->check_ipn_request()) {
      $this->successful_request($IPN_status = true);
    } else {
      $this->successful_request($IPN_status = false);
    }

  }
  
  public function successful_request($IPN_status) {
    $ipn_response = !empty($_POST) ? $_POST : false;
    $ipn_response['IPN_status'] = ( $IPN_status == true ) ? 'Verified' : 'Invalid';
    $posted = stripslashes_deep($ipn_response);
    $this->send_mail($posted);
  }

  /* Start Adding Functions Below this Line */
  public function send_mail($posted) {
    # code...
    $email = isset($posted["payer_email"]) ? $posted["payer_email"] : '';
    $first_name = isset($posted["first_name"]) ? $posted["first_name"] : '' ; 
    $last_name = isset($posted ["last_name"]) ? $posted["last_name"] : '' ;
    $mc_gross = isset($posted["mc_gross"]) ? $posted["mc_gross"] : '';

    $subject = 'Donation Receipt';
    $message = '<html><head></head><body style="background: #fff;">';
    $message .= '<div style="display: block; width: 100%;">
      <table style="width: 100%; border-spacing:0 15px;">
        <tr>
          <td></td>
          <td>
            <img src="https://caprousa.org/wp-content/uploads/2021/04/CAPROUSALOGO101a.png" alt="logo" />
          </td>
          <td></td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td>Dear '. $first_name . ' ' . $last_name .',</td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr>
          <td colspan="3">
            <p>Greetings! We received the sum of $'. $mc_gross . 'USD given to the ministry. Thank you for your partnership. We shall maintain the designation of the donation. </p>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <p> Please‌ ‌use‌ ‌this‌ ‌as‌ ‌a‌ ‌receipt‌ ‌for‌ ‌your‌ ‌donation.‌ ‌We‌ ‌send‌ ‌an‌ ‌end-of-year‌ ‌donation‌ ‌report‌ ‌
              for‌ ‌tax‌ ‌purposes.‌ ‌For‌ ‌more‌ ‌information‌ ‌on‌ ‌any‌ ‌aspect‌ ‌of‌ ‌the‌ ‌ministry,‌ ‌
              kindly‌ ‌contact‌ ‌our‌ ‌office‌ ‌address‌ ‌below. ‌ ‌
              <br />
              Thanks ‌ ‌for ‌ ‌your ‌ ‌participation ‌ ‌ in ‌ ‌ global ‌ ‌ missions.‌ ‌
              <br />
              May‌ ‌God‌ ‌bless‌ ‌you‌ ‌richly ‌ ‌
              <br />
              Thank‌ ‌you‌ ‌very‌ ‌much,‌ ‌
              <br />
              CAPRO‌ ‌Admin‌ ‌
            </p>
          </td>
        </tr>
        <tr>
          <td style="width: 30%"></td>
          <td style="width: 40%">
            <span style="font-style: italic;">
              <q>A gift opens the way and ushers <br />
                the giver into the presence <br />
                of the great</q>. – Proverbs 18:16
            </span>
          </td>
          <td style="width: 30%"></td>
        </tr>
        <tr>
          <td colspan="3">
            <hr />
          </td>
        </tr>
        <tr>
          <td style="border: 2px solid orange; width: 30%;">
            <address style="padding: 0.5rem">
              <strong style="color: #800000">Calvary Ministries CAPROUSA</strong>                                 
              (Admin Office) <br />                                                        
              6363 W 183rd Street, <br />                                                           
              Tinley Park Illinois 60477  <br />                                                    
              Tel: (708) 864-4562    <br />                                                              
              Email: donations@caprousa.org
            </address>
          </td>
          <td style="width: 40%"></td>
          <td style="border: 2px solid orange; width: 30%;">
            <address>
				<strong style="color: #800000">Tax Information</strong> <br />
				EIN: 36-4406559 <br />
				Name:<strong>Calvary Ministries CAPROUSA</strong> <br />
				<strong>The Treasurer: </strong>312, Marshall Ave, STE 1010 <br />
				Laurel, MD 20707, USA <br />
				<strong>Tel:</strong> 301-494- 7023
				<strong>Email:</strong><a href="mailto:donations@caprousa.org">donations@caprousa.org</a>
            </address>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <p style="text-align: justify; padding: 0 10px">
              Our ministry teams are spread to over 34 partnering countries, among over 70 ethnic groups, resulting in the planting of many successful churches. 
            </p>
            <p style="text-align: justify; padding: 0 10px">
              Calvary Ministries (CAPRO USA) is a faith mission. As a faith ministry, the organization and the missionaries depend on God to provide through the freewill offerings of His people. None of our missionaries is on salary. They are all volunteers. Each trusts God to meet their needs. All gifts to CAPRO are used as designated and duly acknowledged. Calvary Ministries (CAPRO USA) is a registered 501c religious organization in the USA (under Section 501(c)3 of the internal revenue code).
            </p>
          </td>
        </tr>
      </table>
    </div>';
    $message .= "</body></html>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <donations@capromissions.org>' . "\r\n";

    if (isset($posted['payer_email']) && !empty($posted['payer_email'])) {
      wp_mail($email, $subject, $message, $headers);
    }

  }

}

function call_donation_button_using_caprousa_email() {
  # code...
  $cp = new Caprousa_Email();
  $cp->load();
}

// Now we set that function up to execute when the pay_pal_ipn action is called.
add_action('plugins_loaded', 'call_donation_button_using_caprousa_email');

 
// Function to change sender name
function wpb_sender_name( $original_email_from ) {
    return 'Calvary Ministries CAPRO USA';
}
 
// Hooking up our functions to WordPress filters
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );

/* Stop Adding Functions Below this Line */
?>