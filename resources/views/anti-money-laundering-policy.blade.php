@extends('layouts.app')

@section('content')
<div id="title" class="page-title">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                Anti-Money Laundering Policy
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="{{ route('home') }}">Home</a><span class="delimiter"></span>Anti-Money Laundering Policy
        </div>
    </div>
</div>

<div id="content" class="site-content" role="main">
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-faq">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Business Name:</strong> Oasis Mint LLC</p>

                        <h2>1. Purpose</h2>
                        <p>This Anti-Money Laundering (AML) Policy is designed to prevent and detect money laundering and the financing of terrorist activities in accordance with the USA PATRIOT Act, Bank Secrecy Act (BSA), and FinCEN guidance, specifically for dealers in precious metals, stones, or jewels (31 CFR Part 1027).</p>

                        <h2>2. Scope</h2>
                        <p>This policy applies to all employees, contractors, and agents of Oasis Mint LLC, involved in the purchase, sale, or brokering of coins, bullion, and other precious metals or collectibles.</p>

                        <h2>3. Regulatory Overview</h2>
                        <p>Precious metals dealers must implement AML programs if they engage in cash transactions over $50,000 in a calendar year. This policy ensures compliance with:</p>
                        <ul>
                            <li>Bank Secrecy Act (BSA)</li>
                            <li>USA PATRIOT Act</li>
                            <li>FinCEN Guidance for Dealers in Precious Metals, Stones, or Jewels</li>
                        </ul>

                        <h2>4. AML Program Components</h2>
                        <p>Our AML Program includes the following four pillars:</p>
                        
                        <h3>a. Internal Controls and Procedures</h3>
                        <ul>
                            <li>Maintain records of all purchases and sales.</li>
                            <li>Verify and document the identity of customers (KYC - Know Your Customer).</li>
                            <li>Monitor and report suspicious activity.</li>
                            <li>Limit acceptance of cash payments above thresholds unless enhanced due diligence is conducted.</li>
                        </ul>

                        <h3>b. Compliance Officer</h3>
                        <ul>
                            <li>Compliance Officer, Oasis Mint LLC is designated as the AML Compliance Officer.</li>
                            <li>Responsibilities: implementation of policy, employee training, recordkeeping, and reporting.</li>
                        </ul>

                        <h3>c. Ongoing Training</h3>
                        <ul>
                            <li>All staff must receive AML training upon hire and at least annually.</li>
                            <li>Training covers red flags, transaction monitoring, and SAR (Suspicious Activity Report) procedures.</li>
                        </ul>

                        <h3>d. Independent Testing</h3>
                        <ul>
                            <li>Independent review of the AML program must be conducted every 12–18 months by a qualified third party or internal auditor not involved in AML operations.</li>
                        </ul>

                        <h2>5. Customer Identification Program (CIP)</h2>
                        <ul>
                            <li>Collect full name, address, date of birth, and government-issued ID for any customer involved in a transaction above $10,000.</li>
                            <li>Keep copies of identification documents for 5 years.</li>
                            <li>For business entities: obtain incorporation documents, EIN, and authorized representative's ID.</li>
                        </ul>

                        <h2>6. Suspicious Activity Monitoring</h2>
                        <p>Transactions may be flagged for review if they involve:</p>
                        <ul>
                            <li>Large cash transactions</li>
                            <li>Structuring (breaking transactions into smaller amounts)</li>
                            <li>Reluctance to provide identification</li>
                            <li>Use of third parties or shell companies</li>
                        </ul>
                        <p><strong>Reporting:</strong></p>
                        <ul>
                            <li>File a Suspicious Activity Report (SAR) with FinCEN within 30 days of detection.</li>
                            <li>File Form 8300 for cash transactions over $10,000.</li>
                        </ul>

                        <h2>7. Recordkeeping</h2>
                        <ul>
                            <li>Keep all AML records, including customer identification and transaction logs, for a minimum of 5 years.</li>
                            <li>Maintain a log of all SARs and Form 8300s submitted.</li>
                        </ul>

                        <h2>8. Enforcement and Penalties</h2>
                        <ul>
                            <li>Violations of this policy may result in disciplinary action, including termination.</li>
                            <li>Willful failure to comply may subject individuals and the business to civil and criminal penalties.</li>
                        </ul>

                        <h2>9. Policy Review</h2>
                        <p>This policy must be reviewed and updated annually or upon any material change in regulations.</p>

                        <p><strong>Approved By:</strong></p>
                        <p>John Doe<br>
                        Managing Member<br>
                        May 11, 2025</p>


                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div><!-- #content -->

@endsection