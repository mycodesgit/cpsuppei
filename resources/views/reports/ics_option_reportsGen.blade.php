<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Return Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header img {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }

        .table-container {
            width: 300px;
            overflow-x: auto;
            margin: 0 auto;
        }

        table {
            width: 300px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-size: 14px;
            word-wrap: break-word;
            white-space: normal;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        td {
            background-color: #ffffff;
        }

        /* Set specific widths for columns to maintain a readable layout */
        th:nth-child(1), td:nth-child(1) { width: 5%; }   /* ITEM NO. */
        th:nth-child(2), td:nth-child(2) { width: 5%; }   /* QTY. */
        th:nth-child(3), td:nth-child(3) { width: 5%; }   /* UNIT */
        th:nth-child(4), td:nth-child(4) { width: 10%; }  /* NAME */
        th:nth-child(5), td:nth-child(5) { width: 20%; }  /* DESCRIPTION */
        th:nth-child(6), td:nth-child(6) { width: 10%; }  /* UNIT VALUE */
        th:nth-child(7), td:nth-child(7) { width: 10%; }  /* TOTAL VALUE */
        th:nth-child(8), td:nth-child(8) { width: 10%; }  /* PROPERTY NUMBER */
        th:nth-child(9), td:nth-child(9) { width: 10%; }  /* DATE ACQUIRED */
        th:nth-child(10), td:nth-child(10) { width: 5%; } /* FUND CODE */
        th:nth-child(11), td:nth-child(11) { width: 10%; } /* END USER */

        /* Optional styling for better readability */
        th, td {
            border-color: #666; /* Border color */
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ asset('uploads/header-return-slip.png') }}" alt="Header Image">
    </header>
    <main class="table-container">
        <table id="rpcppe">
            <thead>
                <tr>
                    <th>ITEM NO.</th>
                    <th>QTY.</th>
                    <th>UNIT</th>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <th>UNIT VALUE</th>
                    <th>TOTAL VALUE</th>
                    <th>PROPERTY NUMBER</th>
                    <th>DATE ACQUIRED</th>
                    <th>FUND CODE</th>
                    <th>END USER</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows of data would go here -->
                <!-- Example row: -->
                <tr>
                    <td>1</td>
                    <td>10</td>
                    <td>pcs</td>
                    <td>Sample Item</td>
                    <td>Item Description that might be long and needs to wrap within the cell to avoid overflow issues.</td>
                    <td>$100.00</td>
                    <td>$1000.00</td>
                    <td>123456</td>
                    <td>2024-11-13</td>
                    <td>FC001</td>
                    <td>End User Name</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
