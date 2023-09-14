<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateReviewedEmail extends Migration
{
    public function up()
    {        
        $html = <<<HTML
        <html>

        <body>
        
            <div>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <style>
                    @media only screen and (max-width:737px) {
                        u + body {
                            -webkit-text-size-adjust: 100%;
                        }
                        .headText {
                            font-size: 25px!important;
                        }
                        .hero_img {
                            width: 478px;
                        }
                    }
        
                    @media only screen and (min-width: 411px) and (max-width: 767px) {
                        .hero_module_head_padding {
                            padding-left: 40px;
                            padding-right: 40px;
                        }
                    }
        
                    @media only screen and (min-width: 320px) and (max-width: 767px) {
                        .main_border {
                            border-left: 5px solid #ffffff;
                        }
                    }
        
                    .forApple_footer a {
                        color: #026621 !important;
                        text-decoration: none !important;
                    }
        
                    .forApple_footer {
                        cursor: text;
                        color: #026621 !important;
                        text-decoration: none !important;
                    }
        
                    @media only screen and (min-width:320px) and (max-width:767px) {
                        table.main_border {
                            border-left: 10px solid #ffffff!important;
                        }
                    }
                </style>
                <div>
                    <table style="max-width: 480px; min-width: 480px;" align="center" width="480" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                        <!--------------Preaheader------------>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table align="center" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:12px;padding-top:10px;padding-bottom:10px;padding-left:30px;padding-right:30px;">
                                                                    SKAPIT - Health and Safety audit.
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <!--------------End Preaheader------------>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="main_border" align="center" width="480" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="border:1px solid #DADCE0;background:#ffffff;">
                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td style="border-bottom:1px solid #DADCE0;">
                                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                                        <!-----------Logo Start ------->
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding-top:20px;padding-bottom:16px;text-align:center">
                                                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="text-align:center;"><img src="https://audit.ski-api-technologies.com/images/ski-api-technologies.png" alt="SKAPIT" width="269px"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <!---------Logo End ------------>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <!----------body_head_text-------------->
                                            <tr>
                                                <td style="padding-top:26px;">
                                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#414141;font-size:22px;text-align:left;padding-left:20px;padding-right:20px;" class="hero_module_head_padding">Health and Safety</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:14px;text-align:left;padding-top:29px;padding-right:50px;padding-left:50px;">Fraser</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:14px;text-align:left;padding-top:29px;padding-right:50px;padding-left:50px;">Hotel Check have completed an audit review.</td>
                                                            </tr>
        
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:14px;text-align:left;padding-top:29px;padding-right:50px;padding-left:50px;">__name__ (type: __type__), __resort__</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:14px;text-align:left;padding-top:29px;padding-right:75px;padding-left:75px;">BA Result: __result_ba__<br/>ABTA Result: __result_abta__<br/>EJH Result: __result_ejh__.</td>
                                                            </tr>
        
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                                            
        
                                                                                <!-----------Cta_button_start-------------->
                                                                                <tr>
                                                                                    <td style="padding-top:29px;">
                                                                                        <table align="center" cellpadding="0" cellspacing="0">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td style="color:#212121;font-size:14px;text-align:center;" width="213">
                                                                                                        <div>
                                                                                                            <!--[if mso]>
                                                                                   <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="__url__" style="height:36px;v-text-anchor:middle;width:213px;" arcsize="12%" strokecolor="#00A095" fillcolor="#00A095">
                                                                                      <w:anchorlock/>
                                                                                      <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">START THE AUDIT</center>
                                                                                   </v:roundrect>
                                                                                   <![endif]--><a href="https://audit.ski-api-technologies.com/audits" style="background-color:#00A095;border:1px solid #00A095;border-radius:4px;color:#ffffff;display:inline-block;font-family:Roboto, Open Sans, arial,sans-serif;font-size:14px;font-weight:medium;line-height:36px;text-align:center;text-decoration:none;width:213px;-webkit-text-size-adjust:none;mso-hide:all;" target="_blank">Go to dashboard</a>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <!-----------End Cta_button_start-------------->                
                                                            
                                                            
                                                            
                                            <tr>
                                                <td style="padding-top:26px;">
                                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                                        <tbody> 
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:14px;text-align:left;padding-top:29px;padding-right:50px;padding-left:50px;">Or, when logged in as an admin you can view the audit in detail <a href='__url__'>here</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <!---------- end body_head_text-------------->
        
                                            <!-----------Under CTA text----------->
                                            <tr>
                                                <td style="padding-top:29px;">
                                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                              <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;color:#7A7A7A;font-size:14px;text-align:left;padding-right:50px;padding-left:50px; padding-bottom:20px;">
                                                                    The Team at SKAPIT.
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
        
                                            <tr>
                                                <td height="30"> </td>
                                            </tr>
        
        
                                            <tr>
                                                <td height="30"> </td>
                                            </tr>
        
                                            <!---------footer start--->
                                            <tr>
                                                <td style="border-bottom:1px solid #DADCE0;padding-top:9px;background:#F8F9FA;">
                                                    <table align="center" width="480" cellpadding="0" cellspacing="0">
                                                        <tbody>
        
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;font-size:9px;color:#757575;padding-top:15px;text-align:center;padding-left:20px;padding-right:20px;">SKAPIT Ltd</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-family:Roboto, Open Sans, arial,sans-serif;font-size:9px;color:#757575;padding-top:15px;text-align:center;padding-left:20px;padding-right:20px;">1 Golden Court<br/>Richmond<br/>Surrey<br/>TW9 1EU<br/>United Kingdom</td>
                                                            </tr>
                                                            <tr>
                                                                <td height="30"> </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        
        </body>
        
        </html>
        HTML;
        
         $sql = 'UPDATE `emails` SET `html` = ? WHERE id = ?';
         $this->db->query($sql, [$html, 6]);
    }

    public function down()
    {

    }
}
?>