<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cli extends MY_Controller
{
    // Not used
    public function offering_annual_reminder()
    {
        exit();
        $this->load->model('offering_model');
        $this->load->library('email');
        
        $offerings = $this->offering_model->get_old_published_repeating();
        foreach ($offerings as $offering) {
            $offering_url = base_url("offering/view/{$offering->id}");
            $offering_date = date('j M, Y', strtotime($offering->start_date));

            $logo = base_url('img/email_logo.png');
            $message = "<!DOCTYPE html><html><body>";
            $message .= "<img src='$logo'>";
            $message .= "<p>This is a reminder email about your recurring offering:</p>";
            $message .= "
            <table>
            <tr>
            <td><b>Offering Name</b></td>
            <td>{$offering->title}</td>
            <td></td>
            </tr>
            <tr>
            <td><b>Start Date</b></td>
            <td>$offering_date</td>
            </tr>
            <tr>
            <td></td>
            <td><a href='$offering_url'>View offering on catalog</a></td>

            </tr>
            </table>
            ";
            $message .= "<p>If your offering is no longer running, or if it requires updating, please reply to this email with the details.</p>";
            $message .= "<p>Thank you!</p>";
            $message .= "</body></html>";

            $this->email->from('edempster@aiminstitute.org', 'AIM Institute');
            $this->email->to($offering->submitter_email);
            if ($offering->contact_email) {
                $this->email->cc($offering->contact_email);
            }
            $this->email->subject('Is your offering still running?');
            $this->email->message($message);
            $this->email->send();
        }
    }

    public function expire_offerings()
    {
        $this->load->model('offering_model');
        $this->offering_model->process_expired();
    }
}
