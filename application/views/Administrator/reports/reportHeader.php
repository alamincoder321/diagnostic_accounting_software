<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px !important;
        }

        body,
        table {
            font-size: 13px;
        }

        table th {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    $branchId = $this->session->userdata('BRANCHid');
    $companyInfo = $this->Billing_model->company_branch_profile($branchId);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <img src="<?php echo base_url(); ?>assets/images/header.jpg" alt="Logo" style="width: 100%; height: 120px; border: 1px solid #ccc; padding: 2px; border-radius: 5px; margin-bottom: 6px;" />
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-xs-12">
                <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
            </div>
        </div> -->
    </div>
</body>

</html>