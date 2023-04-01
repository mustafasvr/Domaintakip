<?php 


class whois extends crud {

    public function whois_server($domain) {

 
                $whois = $this->nSql("SELECT * FROM dp_whois")->fetchAll(PDO::FETCH_ASSOC);

                $dizi = [];

                foreach($whois as $a) {
                    $b = $a['whois_extension'];
                    array_push($dizi,$b);
                }


                $explodedDomain = explode(".", $domain);
                $sayi = count($explodedDomain);
                $explodedCount = $sayi-1;
                $dizisayi = count($dizi);

                $i=0;
                while($i<=$dizisayi-1):
                    $aranan = $dizi[$i];
                    $a =  array_search($aranan,$explodedDomain);
                    if($a) {
                        break;
                    }
                    $i++;
                endwhile;

                if ($a) {

                    if($a < $explodedCount) {
                         $uzanti = $explodedDomain[$a].".".$explodedDomain[$a+1];
                    } else {
                         $uzanti = $explodedDomain[$a];
                    }


                    $parsedomain = $explodedDomain[$a-1];

            
                
                        $awhois = $this->nSql("SELECT * FROM dp_whois WHERE whois_extension='".$uzanti."'")->fetch(PDO::FETCH_ASSOC);
                    
                        if ($awhois) {

                            $data = [];


                            if ($conn = fsockopen ($awhois['whois_server'], 43)) {
                                fputs($conn, $parsedomain.".".$uzanti."\r\n");
                                while(!feof($conn)) {
                                    $data[] .= fgets($conn,128);
                                }
                                fclose($conn);
                            }
                            else { die('Error: Could not connect to ' . $awhois['whois_server'] . '!'); }


                            $domainsKeywords = [
                                [ 'id'			=> ['Domain ID', 'Domain Name ID', 'Registry Domain ID', 'ROID'] ],
                                [ 'domain'		=> ['Domain name', 'Domain Name', 'DOMAIN NAME', 'Domain', 'domain','** Domain Name'] ],
                                [ 'bundled_domain'		=> ['Bundled Domain Name'] ],
                                [ 'dns'			=> ['Name Server', 'Nameservers', 'Name servers', 'Name Servers Information', 'Domain servers in listed order', 'nserver', 'nameservers','** Domain Servers'] ],
                                [ 'registrar'	=> ['Registrar', 'registrar', 'Registrant', 'Registrar Name', 'Created by Registrar'] ],
                                [ 'registrar_url'	=> ['Registrar URL', 'Registrar URL (registration services)'] ],
                                [ 'sponsoring_registrar'	=> [ 'Sponsoring Registrar'] ],
                                [ 'whois_server'	=> ['Whois Server', 'WHOIS SERVER', 'Registrar WHOIS Server'] ],
                                [ 'created'		=> ['Creation Date', 'Created on..............', 'Registration Time', 'Domain Create Date', 'Domain Registration Date', 'Domain Name Commencement Date', 'created'] ],
                                [ 'updated'		=> ['last-update', 'Updated Date', 'Domain Last Updated Date', 'last modified','Last Update Time'] ],
                                [ 'expires'		=> ['Expiry Date', 'Expiration Date', 'Expiration Time', 'Domain Expiration Date', 'Registrar Registration Expiration Date', 'Expires on..............', 'Registry Expiry Date', 'renewal date'] ],
                                [ 'status'		=> ['Status', 'status', 'Domain Status'] ],
                            ];
                    
                            $toBeParseKeywords = [];

                            foreach ($domainsKeywords as $domainKeywords){
                                foreach ($domainKeywords as $var => $keywords){
                                    foreach($keywords as $keyword){
                                        $toBeParseKeywords[$keyword] = $var;
                                    }
                                }
                            } 

                      


                            $parseResult = $this->parse($data,$toBeParseKeywords, true);
        
                            if(isset($parseResult['domain'])) {

    
                            if(isset($parseResult['registrar'][0])) {
                                $kayityeri = $parseResult['registrar'][0];
                            } else {
                                $kayityeri = $data[5];
                            }

                            if(isset($parseResult['dns'])) {
                                $adsunucu = json_encode($parseResult['dns']); 
                            } else {
                                $adsunucu = "-";
                            }

                            $kayit =  $this->domainzaman($parseResult['created'][0]);
                            $guncellenme =  $this->domainzaman($parseResult['updated'][0]);
                            $bitis =  $this->domainzaman($parseResult['expires'][0]);
                       
                            $domain = $parsedomain.".".$uzanti;

                            return array($domain,$kayityeri,$adsunucu,$kayit,$guncellenme,$bitis);
                                                            
                        } else {
                            $response['error'] = "Bu domain kayitli değil.";
                            return $response;
                        }
                            
                        } else {
                            $response['error'] = "Uzanti türü bulunamadı.";
                            return $response;
                        }
                
                } else {
                    
                    $response['error'] = "Uzantı türü desteklenmiyor.";
                    return $response;
                }
            }

            function domainzaman($time) {

                $data = str_replace(array(' '), array('', ''), $time);
                $data = explode(":",$data);
                $data = explode("T",$data[0]);
                $data = strtotime($data[0]);
                return  $data;

            }



        private function parse($data,$keywords)
	    {	
    
		$res = [];

		foreach ($data AS $d)
		{
			$d = trim($d);
	
			
				$pos = strpos($d, ':');

                
				if ($pos !== false)
				{
					$keyword = substr($d, 0, $pos);

                 
					
					if (isset($keywords[$keyword]))
					{
						$t = trim(substr($d, $pos+1));
						if ($t != '')
						{
							$res[$keywords[$keyword]][] = $t;
						}

					}
					else
					{
						$keyword = '';
					} 
				}
			
		}
		return $res;
	}

 

    
            
    }
       