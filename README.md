# CSP-Report #


PHP code to get reports about mixed content when migrating from http to https: 


> header("Content-Security-Policy-Report-Only: default-src https:; style-src 'unsafe-inline' https: ; report-uri https://example.com/csp_report_parser.php;");
