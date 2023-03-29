<?php

use App\Models\Page;
use Illuminate\Database\Seeder;

class SupportPoliciesPageSeeder extends Seeder
{
    use FileCopyTrait;

    public function run()
    {
        $srcImages = [
            'hero' => 'Pages/images/page-hero-bgrs/support-self-wash.jpg',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        $parentPage = Page::where('slug', 'support')->firstOrFail();

        Page::create([
            'parent_page_id' => $parentPage->id,
            'slug' => 'policies',
            'category' => 'main',
            'template' => 'default',
            'title' => 'Policies',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'published_at' => \Carbon\Carbon::now(),
            'previously_saved' => 1,

            'content' => (object)[
                'hero' => (object)[
                    'heading' => 'Privacy Policy and Terms',
                    'image' => $destImages['hero'],
                ],
                'intro' => (object)[
                    'heading' => 'Policies',
                    'paragraphs' => 'We hope to serve you as best we can in all our services. Please see our policies below for more information, and do not hesitate to contact us if you have any questions.',
                ],
                'main' => (object)[
                    'alternatingContentBlocks' => [
                        (object)[
                            'heading' => 'Shipping Policy',
                            'description' => '<p>Orders are processed and shipped within one week. We do not offer express shipping. You will receive an e-mail confirmation after you place an order. This e-mail will contain your order number, which you should use when corresponding with us with questions about your order.</p>'."\n"
                                .'<p>Please note that items ordered together are not necessarily shipped together and may be received in separate shipments.</p>',
                        ],
                        (object)[
                            'description' => '<h3>Domestic Shipping</h3>'."\n"
                                .'<p>Shipping on all orders is free. All orders with PO/APO/FPO addresses can only be shipped via United States Postal Service 7-10 business day delivery. We do not ship COD.</p>'."\n"
                                .'<h3>International Shipping (Including Canada)</h3>'."\n"
                                .'<p>Our international carrier is the US Postal Service. Please allow 4-6 weeks for international delivery. The customer is responsible for all duties and taxes incurred upon delivery. In order to expedite delivery of packages going to international P.O. boxes, we will include your phone number on the outside of the package. If you are concerned about your phone number being displayed on the outside of the package, please call us at 206.789.3700.</p>'."\n"
                                .'<h3>Payments</h3>'."\n"
                                .'<p>A credit card is necessary to place orders over the Internet. We accept payment via Mastercard &amp; Visa. We do not accept mail orders, payment via Paypal or COD.</p>'."\n"
                                .'<h3>Sales Tax</h3>'."\n"
                                .'<p>We automatically charge and withhold sales tax for orders to be delivered to addresses within the State of Washington.</p>'."\n"
                                .'<h3>Changing/Cancelling Your Order</h3>'."\n"
                                .'<p>If you need to cancel your order, please call us at 206.789.3700. If your order has already shipped, you may follow the procedures in our Returns Policy.</p>'."\n"
                                .'<h3>Order Verification</h3>'."\n"
                                .'<p>It is important that you provide us with a valid e-mail address and phone number when placing an order. For the protection of cardholders, we will occasionally delay the shipment of an order pending our ability to verify the order with you.</p>'."\n"
                                .'<h3>Problem with an Order?</h3>'."\n"
                                .'<p>If you have any problems, such as missing or incorrect merchandise, or if you need to cancel or change an order, contact us at 206.789.3700.</p>'."\n"
                                .'<h3>Returns Policy</h3>'."\n"
                                .'<p>You may return most items purchased at BrownBear.com within 30 days of delivery for a full refund. We\'ll also pay the return shipping cost if the return is a result of our error.</p>'."\n"
                                .'<h3>Exchanges</h3>'."\n"
                                .'<p>In the case of defect, we will credit the purchaser the actual return shipping. If there is a problem with your order, please call us at 206.789.3700.</p>'."\n"
                                .'<h3>How to Return or Exchange an Item</h3>'."\n"
                                .'<p>Repack the item securely in its original packaging along with the packing slip and mail the item back to the return address on the packing slip. Indicate on the packing slip if it is a return or exchange. We recommend that you return the item using an insured carrier such as FedEx, UPS, or USPS Parcel Post, and that you insure the item for its full value. Postage or courier fees for the return shipment must be prepaid.</p>'."\n"
                                .'<p>Send returns to the following address:</p>'."\n"
                                .'<p><strong>Car Wash Enterprises, Inc.<br />3977 Leary Way NW<br />Seattle, WA 98107</strong></p>'."\n"
                                .'<h3>Refund Policy</h3>'."\n"
                                .'<p>We\'ll notify you via e-mail of your refund once we\'ve received and processed the returned item. You can expect a refund in the same form of payment originally used for purchase within 7 to 14 business days of our receiving your return.</p>'."\n"
                                .'<p>If the return and refund does not arrive after 4 weeks from the day you sent us your return, please contact us for further assistance.</p>'."\n"
                                .'<h3>How Refunds Are Calculated</h3>'."\n"
                                .'<p>Items that meet our return guidelines will receive a full refund. We do not issue partial refunds.</p>',
                        ],
                        (object)[
                            'heading' => 'Privacy Policy',
                            'description' => '<p>Thank you for visiting Car Wash Enterprises, Inc. Your privacy is important to us. To better protect your privacy, we provide this notice explaining our online information practices and the choices you can make about the way your information is collected and used at this site.</p>',
                        ],
                        (object)[
                            'description' => '<h3>The Information We Collect</h3>'."\n"
                                .'<p>On this site, the types of personally identifiable information that may be collected from these pages include: name, address, e-mail address, telephone number, fax number, credit card information and information about your interests in, and use of, various products.</p>'."\n"
                                .'<p>We also may collect certain non-personally identifiable information when you visit many of our web pages such as the type of browser you are using (e.g., Netscape, Internet Explorer), the type of operating system you are using, (e.g., Windows 95 or Mac OS) and the domain name of your Internet service provider (e.g., America Online, Earthlink).</p>'."\n"
                                .'<h3>How We Use the Information</h3>'."\n"
                                .'<p>We may use the information you provide about yourself to fulfill your requests for our products, to respond to your inquiries about our offerings, and to offer you other products that we believe may be of interest to you.</p>'."\n"
                                .'<p>We sometimes use this information to communicate with you, such as to notify you when we make changes to our subscriber agreements or to contact you about your account with us.</p>'."\n"
                                .'<p>We sometimes use the non-personally identifiable information that we collect to improve the design and content of our site and to enable us to personalize your Internet experience. We also may use this information in the aggregate to analyze site usage, as well as to offer you additional products.</p>'."\n"
                                .'<p>We may disclose personally identifiable information in response to legal process, for example, in response to a court order or a subpoena. We also may disclose such information in response to a law enforcement agency\'s request.</p>'."\n"
                                .'<p>Agents and contractors of Car Wash Enterprises, Inc. who have access to personally identifiable information are required to protect this information in a manner that is consistent with this Privacy Notice by, for example, not using the information for any purpose other than to carry out the services they are performing for Car Wash Enterprises, Inc.</p>'."\n"
                                .'<p>Although we take appropriate measures to safeguard against unauthorized disclosures of information, we cannot assure you that personally identifiable information that we collect will never be disclosed in a manner that is inconsistent with this Privacy Policy.</p>'."\n"
                                .'<p>Finally, we will not use or transfer personally identifiable information provided to us in ways unrelated to the ones described above without also providing you with an opportunity to opt out of these unrelated uses.</p>'."\n"
                                .'<h3>Collection of Information by Third-Party Sites and Sponsors</h3>'."\n"
                                .'<p>The Car Wash Enterprises, Inc. website may contain links to other sites whose information practices may be different than ours. Visitors should consult the other sites\' privacy policies as we have no control over information that is submitted to, or collected by, these third parties.</p>'."\n"
                                .'<p>Sometimes, we may offer content (e.g., contests, sweepstakes, or promotions) that is sponsored by, or co-branded with, identified third parties. By virtue of these relationships, the third parties may obtain personally identifiable information that visitors voluntarily submit to participate in the site activity. We have no control over these third parties\' use of this information. We will notify you at the time of requesting personally identifiable information if these third parties will obtain such information.</p>'."\n"
                                .'<h3>Cookies</h3>'."\n"
                                .'<p>To enhance your experience with our site, many of our Web pages use "cookies." Cookies are text files we place in your computer\'s browser to store your preferences. Cookies, by themselves, do not tell us your e-mail address or other personally identifiable information unless you choose to provide this information to us by, for example, registering at our site. However, once you choose to furnish personally identifiable information, this information may be linked to the data stored in the cookie.</p>'."\n"
                                .'<p>We use cookies to understand site usage and to improve the content and offerings. For example, we may use cookies to personalize your experience at our web pages (e.g. to recognize you by name when you return to our site), save your password in password-protected areas, and enable you to use shopping carts on our site. We also may use cookies to offer you additional products.</p>'."\n"
                                .'<h3>Our Commitment to Security</h3>'."\n"
                                .'<p>We have put in place appropriate physical, electronic and managerial procedures to safeguard and help prevent unauthorized access, maintain data security and correctly use the information we collect online.</p>'."\n"
                                .'<p>We are also an Authorize.Net Verified Merchant.</p>'."\n"
                                .'<h3>How to Contact Us</h3>'."\n"
                                .'<p>If you have any questions or concerns about the Car Wash Enterprises, Inc. online policy or its implementation, you may contact us at 206.789.3700.</p>',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
