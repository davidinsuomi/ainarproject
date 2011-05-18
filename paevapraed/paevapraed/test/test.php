<?php
$mbox = imap_open( "{mail.paevapraed.com:143/notls}", "info@paevapraed.com", "Pilt17" );
$mid = imap_num_msg( $mbox );
echo $mid."<br/>";
if( $mid >0 ) {
	
	$struct = imap_fetchstructure($mbox, $mid);
	
	$parts = $struct->parts;
	$i = 0;
	
	if (!$parts) { /* Simple message, only 1 piece */
		$attachment = array(); /* No attachments */
		$content = imap_body($mbox, $mid);
	} else { /* Complicated message, multiple parts */
		
		$endwhile = false;
		
		$stack = array(); /* Stack while parsing message */
		$content = "";    /* Content of message */
		$attachment = array(); /* Attachments */
		
		while (!$endwhile) {
			if (!$parts[$i]) {
				if (count($stack) > 0) {
					$parts = $stack[count($stack)-1]["p"];
					$i     = $stack[count($stack)-1]["i"] + 1;
					array_pop($stack);
				} else {
					$endwhile = true;
				}
			}
			
			if (!$endwhile) {
				/* Create message part first (example '1.2.3') */
				$partstring = "";
				foreach ($stack as $s) {
					$partstring .= ($s["i"]+1) . ".";
				}
				$partstring .= ($i+1);
				
				if (strtoupper($parts[$i]->disposition) == "ATTACHMENT") { /* Attachment */
					$attachment[] = array("filename" => $parts[$i]->parameters[0]->value,
						"filedata" => imap_fetchbody($mbox, $mid, $partstring));
				} elseif (strtoupper($parts[$i]->subtype) == "PLAIN") { /* Message */
					$content .= imap_fetchbody($mbox, $mid, $partstring);
				}
			}
			
			if ($parts[$i]->parts) {
				$stack[] = array("p" => $parts, "i" => $i);
				$parts = $parts[$i]->parts;
				$i = 0;
			} else {
				$i++;
			}
		} /* while */
	} /* complicated message */
	
	echo "Analyzed message $mid, result: <br />";
	echo "Content: $content<br /><br />";
	echo "Attachments:"; print_r ($attachment);
}

imap_close( $mbox );
?>
