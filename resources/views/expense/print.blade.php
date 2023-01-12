<html>
<head>
    <style>
        /**
            Set the margins of the page to 0, so the footer and the header
            can be of the full height and width !
         **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }
    </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->
<header>
    <img src="header.png" width="100%" height="100%"/>
</header>

<footer>
    <img src="footer.png" width="100%" height="100%"/>
</footer>

<!-- Wrap the content of your PDF inside a main tag -->
<main>
    <p><span class="bold">Date: </span> {{$expense->nepali_date}}</p>
    <br>
    <p><span class="bold">Amount: </span> {{$expense->amount}}</p>
    <br>
    <p><span class="bold">Category: </span> {{$expense->category}}</p>
    <br>
    <p><span class="bold">Payment Mode: </span> {{$expense->mode}}</p>
    <br>
    <p><span class="bold">Details: </span> {{$expense->details}}</p>
    <br>
    <p><span class="bold">TXN No.: </span> {{empty($expense->txn_no)? $expense->txn_no : '-'}}</p>
    <br>
    <p><span class="bold">Payee: </span> {{empty($expense->payee)? $expense->payee : '-'}}</p>
    <br>
    <p><span class="bold">Receiver: </span> {{empty($expense->receiver)? $expense->receiver : '-'}}</p>
    <br>
    <p><span class="bold">Paid By: </span> {{$expense->user->name}}</p>
</main>
</body>
</html>
