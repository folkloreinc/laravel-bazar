<?='<?xml version="1.0" encoding="UTF-8"?>'?>
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v3">
    
    <customer-number><?=$customerNumber?></customer-number>
    
    <parcel-characteristics>
        <?php if(isset($weight)) { ?>
        <weight><?=$weight?></weight>
        <?php } ?>
        <?php if(isset($length) || isset($width) || isset($height)) { ?>
        <dimensions>
            <?php if(isset($length)) { ?>
            <length><?=$length?></length>
            <?php } ?>
            <?php if(isset($width)) { ?>
            <width><?=$width?></width>
            <?php } ?>
            <?php if(isset($height)) { ?>
            <height><?=$height?></height>
            <?php } ?>
        </dimensions>
        <?php } ?>
    </parcel-characteristics>
    
    <origin-postal-code><?=strtoupper($from)?></origin-postal-code>
    
    <destination>
        <?php if(!isset($to['country']) || $to['country'] === 'CA') { ?>
        <domestic>
            <postal-code><?=strtoupper(!isset($to['postalcode']) ? $to:$to['postalcode'])?></postal-code>
        </domestic>
        <?php } else if($to['country'] === 'US') { ?>
        <united-states>
            <zip-code><?=$to['postalcode']?></zip-code>
        </united-states>
        <?php } else { ?>
        <international>
            <country-code><?=strtoupper($to['country'])?></country-code>
        </international>
        <?php } ?>
    </destination>
    
</mailing-scenario>
