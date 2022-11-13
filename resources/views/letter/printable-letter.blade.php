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
    <p><span class="bold">Date: </span> {{$letter->nepali_date}}</p>
    <br>
    <p class="bold">To,</p>
    <p>{{$letter->to}}</p>
    <p>{{$letter->address}}</p>
    <br>
    <p>Subject: {{$letter->subject}}</p>
    <br>
    <p>{{$letter->body}}</p>
    <br>
    <p>{{$letter->signed_by}}</p>
    <p>{{$letter->designation}}</p>
    <p>Klin Laundromat Pvt. Ltd</p>
</main>
</body>
</html>
