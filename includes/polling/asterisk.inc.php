<?php

// file_put_contents('/tmp/asterisk.inc.txt', var_export($device, true));

if ($device['type'] == 'voip')
{
    echo("VOIP:\n");
    include("includes/include-dir-mib.inc.php");

    $asterisk_rrd = "asterisk.rrd";


    $asterisk_proc_calls = snmp_walk($device, "astConfigCallsProcessed", "-OUqnv", "ASTERISK-MIB", $mibdir, FALSE) + 0;
    echo("Asterisk processed calls: " . $asterisk_proc_calls . "\n");

    $ast_channels = snmp_walk($device, "astNumChannels",  "-Oq", "ASTERISK-MIB", $mibdir, FALSE) + 0;
    $ast_bridged = snmp_walk($device, "astNumChanBridge", "-Oq", "ASTERISK-MIB", $mibdir, FALSE) + 0;

    // echo(" " . snmp_walk($device, "astConfigUpTime", "-Oq", "ASTERISK-MIB", $mibdir) . "\n");
    // echo(" " . snmp_walk($device, "astChanTypeName", "-Oq", "ASTERISK-MIB", $mibdir) . "\n");
    // echo(" " . snmp_walk($device, "astChanTypeName", "-Oq", "ASTERISK-MIB", $mibdir) . "\n");
    // echo(" " . snmp_walk($device, "astChanTypeChannels", "-Oq", "ASTERISK-MIB", $mibdir) . "\n");

    rrdtool_create($device, $asterisk_rrd, " \
        DS:asterisk_processed:GAUGE:600:0:U \
        DS:asterisk_channels:GAUGE:600:0:U \
        DS:asterisk_bridged:GAUGE:600:0:U \
        ");

    rrdtool_update($device, $asterisk_rrd, array($asterisk_proc_calls, $ast_channels, $ast_bridged));

    $graphs['asterisk_processed'] = TRUE;
    $graphs['asterisk_channels'] = TRUE;
    $graphs['asterisk_bridged'] = TRUE;

}

echo(PHP_EOL);

// EOF

