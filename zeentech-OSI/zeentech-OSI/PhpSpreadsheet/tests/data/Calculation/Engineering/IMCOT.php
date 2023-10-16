<?php

use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;

return [
    [
        '-0.0000104004141424230319-1.00002138037057154j',
        '12.34+5.67j',
    ],
    [
        ExcelError::NAN(),
        'Invalid Complex Number',
    ],
    [
        '0.00894394174578834370-1.01017158348808170i',
        '3.5+2.5i',
    ],
    [
        '0.218391793398543914-1.20562055667579681i',
        '3.5+i',
    ],
    [
        '2.66961648496886604',
        '3.5',
    ],
    [
        '0.218391793398543914+1.20562055667579681i',
        '3.5-i',
    ],
    [
        '0.00894394174578834370+1.01017158348808170i',
        '3.5-2.5i',
    ],
    [
        '0.0121847112919806296-0.994333285407756555i',
        '1+2.5i',
    ],
    [
        '0.217621561854402681-0.868014142895924949i',
        '1+i',
    ],
    [
        '0.642092615934330703',
        '1',
    ],
    [
        '0.217621561854402681+0.868014142895924949i',
        '1-i',
    ],
    [
        '0.0121847112919806296+0.994333285407756555i',
        '1-2.5i',
    ],
    [
        '-1.01356730981260846i',
        '2.5i',
    ],
    [
        '-1.31303528549933130i',
        'i',
    ],
    [
        INF,
        '0',
    ],
    [
        '1.31303528549933130i',
        '-i',
    ],
    [
        '1.013567309812609i',
        '-2.5i',
    ],
    [
        '-0.01218471129198063-0.994333285407757i',
        '-1+2.5i',
    ],
    [
        '-0.21762156185440268-0.8680141428959249i',
        '-1+i',
    ],
    [
        '-0.642092615934330703',
        '-1',
    ],
    [
        '-0.21762156185440268+0.8680141428959249i',
        '-1-i',
    ],
    [
        '-0.01218471129198063+0.994333285407757i',
        '-1-2.5i',
    ],
    [
        '-0.00894394174578834-1.010171583488082i',
        '-3.5+2.5i',
    ],
    [
        '-0.2183917933985438-1.205620556675797i',
        '-3.5+i',
    ],
    [
        '-2.66961648496886604',
        '-3.5',
    ],
    [
        '-0.2183917933985438+1.205620556675797i',
        '-3.5-i',
    ],
    [
        '-0.00894394174578834+1.010171583488082i',
        '-3.5-2.5i',
    ],
    [
        '-7.01525255143453347',
        '3',
    ],
];
