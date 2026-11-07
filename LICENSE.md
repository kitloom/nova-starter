> This skeleton is public so that `composer create-project` and `laravel new --using`
> can fetch it. The kitloom packages it requires come from the kitloom Composer
> repository with a purchased license. The agreement below covers both.

# kitloom Commercial License Agreement

Last updated: 2026-11-06

Copyright © 2026 Kitloom. All rights reserved.

This Commercial License Agreement (the "Agreement") is between **Kitloom**, a business name registered in Manitoba, Canada under Registry No. 10284043 ("Licensor", "we", "us"), and the business or individual developer purchasing or otherwise obtaining a license ("Licensee", "you").

By purchasing a license, installing, or using any commercial kitloom package (the "Software"), you agree to this Agreement. If you do not agree, do not install or use the Software.

## 1. Business Use Only

The Software is sold exclusively to businesses and professional developers for use in their trade, business or profession. It is not sold to consumers for personal, family or household purposes. By accepting this Agreement, you confirm that you are acquiring the Software for business purposes.

## 2. Definitions

- **"Product"** means the edition you licensed: **Kitloom for Filament** or **Kitloom for Nova**, as described at https://kitloom.dev/pricing.
- **"Software"** means the commercial kitloom packages included in your Product, including packages added to it during your Update Period, their source code, assets, documentation and any Updates.
- **"Project"** means one production website or application, served from one primary domain and its subdomains, regardless of the number of servers.
- **"Development Environments"** means local, testing and staging copies of a Project that are not publicly used. They do not count as separate Projects.
- **"Updates"** means new versions, fixes and improvements of the Software released during the Update Period.
- **"Update Period"** means 12 months from the date of purchase or renewal.
- **"Client"** means a third party for whom you build or maintain a Project.

## 3. License Grant

Subject to this Agreement and payment of the applicable fee, we grant you a non-exclusive, non-transferable (except as stated in Section 5), worldwide license to install, use and modify the Software:

- **Single Project license:** in one Project and its Development Environments.
- **Unlimited license:** in an unlimited number of Projects owned by you or built by you for your Clients, and their Development Environments.

The license is perpetual for the versions of the Software released during your Update Period. You may keep using those versions after the Update Period ends.

## 4. Restrictions

You may not:

1. sell, resell, sublicense, rent, lend or distribute the Software or any part of it, whether original or modified, except as part of a Project as allowed in Section 5;
2. publish the Software or its source code in any public repository, package registry, marketplace or file-sharing service;
3. share your license key or package access credentials with anyone who is not your employee or contractor working on your Projects;
4. use the Software, in whole or in part, to create a product that competes with the Software, such as a CMS, an admin panel, or a package, plugin, theme or template that offers the Software's features to people who have no license for it;
5. remove or alter any copyright, license or attribution notices in the Software;
6. use the Software in more Projects than your license allows.

**Your own extensions.** Restrictions 1, 2 and 4 do not cover modules, themes and other extensions you make for the Software that need the Software to run. You may use, sell or publish them under a license of your choice, free or paid, as long as they do not include the Software or substantial parts of it and say that their users need their own license for the Software. Code that the Software's generators (such as `kitloom:make-module` and `kitloom:make-theme`) write for you is yours and may be part of such an extension.

## 5. Agencies and Clients

You may use the Software to build Projects for your Clients.

- With a **Single Project license**, each Client Project requires its own license. You may transfer that license to the Client when you hand over the Project.
- With an **Unlimited license**, your Client Projects are covered as long as you build or maintain them. If a Client takes over a Project and you no longer maintain it, the Client must obtain its own license to receive Updates.

Your Client may use and modify the Software as part of its Project, but may not extract it for use in other Projects or redistribute it.

## 6. Updates and Renewal

During the Update Period, you may download Updates through the private package repository, using the access credentials we issue for your license. After the Update Period ends, you may renew your license at the renewal price in effect at that time to receive further Updates. Without renewal, the Software keeps working, but you will not receive new versions.

The Software contains no license verification; the access credentials are needed only to download from the private package repository. You may keep copies of the packages you have downloaded — in your version control or in a private package repository of your own — and continue to install them in the Projects your license covers, during and after the Update Period and whether or not the private package repository remains available.

The only connection the Software makes to our servers is the update check, which says nothing about your license and does not stop the Software from working. To tell your administrators that an Update is out, the Software asks our update server for the list of releases twice a day, and when an administrator asks for it, sending only the Software's version, the PHP version, the edition and a random identifier of the installation — not the site's address, its modules, users or content (see our [Privacy Policy](https://kitloom.dev/privacy)). You can turn the check off with `KITLOOM_UPDATE_CHECK=false`.

## 7. Ownership

The Software is licensed, not sold. We retain all right, title and interest in the Software, including all copyrights, trademarks and other intellectual property rights. The name "kitloom" and related logos are our trademarks and may not be used to name or promote other products without our written permission.

Modifications you make to the Software for your own Projects belong to you, but they remain subject to this Agreement.

## 8. Third-Party Software, Data, Services and Trademarks

The Software works together with third-party software, including Laravel, Filament and, where applicable, Laravel Nova. Those components are governed by their own licenses. This Agreement does not grant you a license to any third-party software. Laravel Nova requires a separate license from its vendor.

**Other products' data.** The Software reads and writes data in the formats that WordPress and some of its plugins use (for example WooCommerce, Advanced Custom Fields, Contact Form 7 and Rank Math), and can import data that other plugins left in the database (for example Yoast SEO and Redirection). It contains no code of WordPress or of those plugins and does not require them to be installed. Working with their data gives neither you nor us any license to those products.

**Trademarks.** WordPress, WooCommerce, Advanced Custom Fields, Contact Form 7, Rank Math, Yoast SEO, Redirection, Laravel, Filament, Nova and the other product names used in the Software and its documentation are trademarks of their respective owners. They are used only to say what the Software works with. Kitloom is not affiliated with, endorsed or sponsored by their owners.

**Third-party services.** Some features connect to services that you choose and set up with your own account or key, such as AI model providers, search engines' indexing APIs, payment providers, and email, storage or analytics services. Your use of those services is governed by their terms. You are responsible for their fees, for the content and personal data you send to them, and for complying with the law that applies when you do. We do not control those services and are not responsible for their availability, for changes to them, or for what they return, including text generated by AI models. Have a person review AI-generated content before it is published.

## 9. Refunds

If the Software does not work for you, you may request a full refund within **14 days** of your initial purchase, without having to give a reason. Renewals are refundable within 14 days of the renewal date. After a refund, your license is cancelled and you must stop using the Software and delete it from your Projects.

## 10. Support

Support is provided on a reasonable-efforts basis through the channels listed at https://kitloom.dev during your Update Period. We do not guarantee response times or that every issue will be resolved, unless agreed separately in writing.

## 11. Disclaimer of Warranties

To the maximum extent permitted by applicable law, the Software is provided **"as is" and "as available"**, without warranties or conditions of any kind, whether express, implied or statutory, including any implied warranties or conditions of merchantable quality, fitness for a particular purpose, title and non-infringement. We do not warrant that the Software will be error-free, secure or uninterrupted, or that it will work with every version of WordPress data, of the data WordPress plugins store, Laravel, Filament or Laravel Nova. An import of another plugin's data is made on a best-effort basis: some of it may not be carried over or may change on the way, as the Software reports.

You are responsible for backing up your data and testing the Software before using it in production, especially before running it against an existing database or importing another plugin's data.

## 12. Limitation of Liability

To the maximum extent permitted by applicable law:

1. we are not liable for any indirect, incidental, special, consequential or punitive damages, or for loss of profits, revenue, data or business opportunities, even if we were advised of their possibility;
2. our total liability arising out of or relating to this Agreement or the Software is limited to the amount you paid us for the Software in the 12 months before the event giving rise to the claim.

## 13. Termination

This Agreement and your license terminate automatically if you breach it. We may also revoke your license key in that case. On termination, you must stop using the Software and delete all copies. Sections 4, 7, 11, 12, 14 and 15 survive termination.

## 14. Governing Law and Disputes

This Agreement is governed by the laws of the Province of Manitoba and the federal laws of Canada applicable therein, without regard to conflict-of-law rules. The courts located in Winnipeg, Manitoba have exclusive jurisdiction over any dispute arising out of or relating to this Agreement. The United Nations Convention on Contracts for the International Sale of Goods does not apply.

## 15. General

- **Entire agreement.** This Agreement is the entire agreement between you and us about the Software and replaces any prior understandings.
- **Changes.** We may update this Agreement for future purchases and renewals. The version you accepted applies to your current Update Period.
- **Resellers.** If you buy through a reseller or payment platform, that platform processes payment only. This Agreement is still between you and us.
- **Severability.** If any provision is found unenforceable, the rest of the Agreement remains in effect.
- **No waiver.** Not enforcing a provision is not a waiver of our right to enforce it later.
- **Assignment.** You may not assign this Agreement except as allowed in Section 5. We may assign it in connection with a sale or reorganization of our business.

## 16. Contact

Kitloom
Manitoba, Canada, Registry No. 10284043
support@kitloom.dev
https://kitloom.dev
