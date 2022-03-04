<?php
require_once './include/const.php';
require_once './include/fonctions.php';
require_once './class/model.php';

class decree {
	
	private $_dbcon;
	
	private $_year;
	
	private $_number;
	
	private $_id;
	
	private $_idmodel;
	
	function __construct($dbcon, $year=null, $number=null, $id=null)
	{
		require_once ("./include/dbconnection.php");
		if ($year != null && $number != null)
		{
			$this->_year = intval($year);
			$this->_number = intval($number);
		}
		if ($id != null)
		{
			$this->_id = intval($id);
		}
		$this->_dbcon = $dbcon;
	}
	
	function getid()
	{
		if (isset($this->_id) && $this->_id != null)
		{
			return $this->_id;
		}
		$select = "SELECT iddecree FROM decree WHERE number = ? AND year = ?";
		$params = array($this->getNumber(), $this->getYear());
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$this->_id = $res['iddecree'];
				return $res['iddecree'];
			}
			else 
			{
				elog("decree ".$this->getYear()." / ".$this->getNumber()." absent de la table.");
			}
		}
		else 
		{
			elog("erreur select iddecree from decree.".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	
	function getNumber()
	{
		if (isset($this->_number) && $this->_number != null)
		{
			return $this->_number;
		}
		$select = "SELECT number FROM decree WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$this->_number = $res['number'];
				return $res['number'];
			}
			else
			{
				elog("decree $this->_id absent de la table.");
			}
		}
		else
		{
			elog("erreur select number from decree.".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	
	function getYear()
	{
		if (isset($this->_year) && $this->_year != null)
		{
			return $this->_year;
		}
		$select = "SELECT year FROM decree WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$this->_year = $res['year'];
				return $res['year'];
			}
			else
			{
				elog("decree $this->_id absent de la table.");
			}
		}
		else
		{
			elog("erreur select year from decree.".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function getModelId()
	{
		//print_r2("getModelId");
		if (isset($this->_idmodel) && $this->_idmodel != null)
		{
			return $this->_idmodel;
		}
		$select = "SELECT idmodel FROM decree WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$this->_idmodel = $res['idmodel'];
				return $res['idmodel'];
			}
			else
			{
				elog("decree $this->_id absent de la table.");
			}
		}
		else
		{
			elog("erreur select idmodel from decree.".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function getDecree()
	{
		$select = "SELECT * FROM decree WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		$decree = NULL;
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$decree = $res;
			}
		}
		else
		{
			elog("erreur SELECT * FROM decree WHERE id =". $this->getid()." ".mysqli_error($this->_dbcon));
		}
		return $decree;
	}
	
	function setIdEsignature($id)
	{
		$update = "UPDATE decree SET idesignature = ?, status = 'p' WHERE iddecree = ?";
		$params = array($id, $this->getId());
		$result = prepared_query($this->_dbcon, $update, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			elog("identifiant esignature : ".$id." pour le decree id : ".$this->getId());
		}
		else
		{
			elog("Erreur update idesignature : ".$id." pour le decree id : ".$this->getId()." ".mysqli_error($this->_dbcon));
		}
	}
	
	function unsetIdEsignature($userid)
	{
		$update = "UPDATE decree SET idesignature = NULL, status = 'a', idmajuser = ?, majdate = NOW() WHERE iddecree = ?";
		$params = array($userid, $this->getId());
		$result = prepared_query($this->_dbcon, $update, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			elog("identifiant esignature supprime pour le decree id : ".$this->getId());
		}
		else
		{
			elog("Erreur suppression idesignature pour le decree id : ".$this->getId()." ".mysqli_error($this->_dbcon));
		}
	}
	
	
	function getIdEsignature()
	{
		$select = "SELECT idesignature FROM decree WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				return $res['idesignature'];
			}
		}
		else
		{
			elog("Erreur select idesignature pour le decree id : ".$this->getId()." ".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function setStatus($status)
	{
		$update = "UPDATE decree SET status = ? WHERE iddecree = ?";
		$params = array($status, $this->getId());
		$result = prepared_query($this->_dbcon, $update, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			elog("status esignature : ".$status." pour le decree id : ".$this->getId());
		}
		else
		{
			elog("Erreur update status esignature : ".$status." pour le decree id : ".$this->getId()." ".mysqli_error($this->_dbcon));
		}
	}
	
	
	function getStatus()
	{
		$select = "SELECT status FROM decree WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$status = $res['status'];
				if ($status == 'p')
				{
					return $this->synchroEsignatureStatus($status);
				}
				return $status;
			}
		}
		else
		{
			elog("Erreur select status pour le decree id : ".$this->getId()." ".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function save($iduser, $idmodel, $structure, $idesignature=null, $status = 'b')
	{
		if ($idesignature != null)
		{
			$insert = "INSERT INTO decree (`year`, `number`, `createdate`, `iduser`, `idmodel`, `idesignature`, `status`, `structure`) VALUES (?, ?, NOW(), ?, ?, ?, 'p', ?)";
			$params = array($this->_year, $this->_number, $iduser, $idmodel, $idesignature, $structure);
		}
		else 
		{
			$insert = "INSERT INTO decree (`year`, `number`, `createdate`, `iduser`, `idmodel`, `status`, `structure`) VALUES (?, ?, NOW(), ?, ?, ?, ?)";
			$params = array($this->_year, $this->_number, $iduser, $idmodel, $status, $structure);
		}
		$result = prepared_query($this->_dbcon, $insert, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			elog("Decree cree : ".$this->_number." / ".$this->_year);
		}
		else
		{
			elog("Erreur insert Decree : ".$this->_number." / ".$this->_year." ".mysqli_error($this->_dbcon));
		}
	}
	
	function setFields($fields)
	{
		foreach ($fields as $field)
		{
			$insert = "INSERT INTO decree_field (`iddecree`, `idmodel_field`, `value`) VALUES (?, ?, ?)";
			$params = array($this->getid(), $field['idmodel_field'], $field['value']);
			$result = prepared_query($this->_dbcon, $insert, $params);
			if ( !mysqli_error($this->_dbcon))
			{
				elog("Field cree pour le decree ".$this->getNumber()." : ".var_export($field, true));
			}
			else
			{
				elog("Erreur insert Field : ".$this->getNumber()." ".mysqli_error($this->_dbcon));
			}
		}
	}
	function getFields()
	{
		$select = "SELECT * FROM decree_field WHERE iddecree = ?";
		$params = array($this->getId());
		$result = prepared_select($this->_dbcon, $select, $params);
		$fields = array();
		if ( !mysqli_error($this->_dbcon))
		{
			while ($row = mysqli_fetch_assoc($result))
			{
				$fields[$row['idmodel_field']][] = $row;
			}
		}
		else
		{
			elog("Erreur select decree_field : ".$this->getid()." ".mysqli_error($this->_dbcon));
		}
		return $fields;
	}
	
	function unsetNumber($iduser)
	{
		$update = "UPDATE decree SET number = NULL, idmajuser = ?, majdate = NOW(), status = 'a' WHERE iddecree = ?";
		$params = array($iduser, $this->getid());
		$result = prepared_query($this->_dbcon, $update, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			$this->_number = null;
			$this->_year = null;
			elog("Numéro ".$this->_year.'/'.$this->_number." supprimé pour le decree id : ".$this->getid()." par iduser ".intval($iduser));
		}
		else
		{
			elog("Erreur à la suppression du numéro ".$this->_year.'/'.$this->_number." du decree : ".$this->getId()." ".mysqli_error($this->_dbcon));
		}
	}
	
	function getModel()
	{
		//print_r2("getModel");
		$idmodel = $this->getModelId();
		$model = new model($this->_dbcon, $idmodel);
		return $model;
	}
	
	function getFileName($extension='pdf')
	{
		//print_r2("getFileName");
		$filename = "";
		$model = $this->getModel();
		$filename .= substr($model->getfile(), 0, -4).$this->getYear().'_'.$this->getNumber().".".$extension;
		return $filename;
	}
	
	function synchroEsignatureStatus($status)
	{
		$idesignature = $this->getIdEsignature();
		if ($idesignature == 0) 
		{
			elog("Pas de synchronisation du document ".$this->getId()." pas d'identifiant eSignature.");
		}
		else 
		{
			elog("Synchronisation... ".ESIGNATURE_CURLOPT_URL_GET_SIGNREQ . $idesignature);
			$curl = curl_init();
			$opts = array(
					CURLOPT_URL => ESIGNATURE_CURLOPT_URL_GET_SIGNREQ . $idesignature,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_PROXY => ''
			);
			curl_setopt_array($curl, $opts);
			//curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			$json = curl_exec($curl);
			
			$error = curl_error ($curl);
			curl_close($curl);
			if ($error != "")
			{
				elog(" Erreur Curl =>  " . $error);
			}
			//echo "<br>" . print_r($json,true) . "<br>";
			$response = json_decode($json, true);
			//print_r2($response);
			
			//elog("Le json est =>  " . var_export($json,true));
			
			//elog("La réponse est =>  " . var_export($response,true));
			
			if (stristr(substr($json,0,20),'HTML') === false)
			{
				if (! isset($response['error']))
				{
					elog ("Succès. setStatus...");
					if (isset($response['parentSignBook']['status']))
					{
						$current_status = $response['parentSignBook']['status'];
					}
					else
					{
						$current_status = '';
					}
					elog("current status ".$current_status);
					switch (strtolower($current_status))
					{
						//draft, pending, canceled, checked, signed, refused, deleted, completed, exported, archived, cleaned
						case 'draft' :
						case 'pending' :
						case 'signed' :
						case 'checked' :
							$new_status = 'p'; // pending
							break;
						case 'completed' :
						case 'exported' :
						case 'archived' :
						case 'cleaned' :
							$new_status = 'v'; // validated
							break;
						case 'refused':
							$new_status = 'r'; // refused
							break;
						case 'deleted' : // TODO : Attention le document est dans la corbeille
						case 'canceled' :
						case '' :
							$new_status = 'a'; // aborted
							break;
						default :
							$new_status = 'e'; // error
					}
					elog ("Nouveau statut de la demande : ".$new_status);
					if ($status != $new_status)
					{
						$this->setStatus($new_status);
					}
					return $new_status;
				}
				else 
				{
					elog("La réponse est en erreur.");
				}
			}
			else 
			{
				elog("Le json est du HTML.");
			}
		}
		return null;
	}
	
	function deleteSignRequest($userid)
	{
		/*$eSignature_url = $fonctions->liredbconstante("ESIGNATUREURL"); //"https://esignature-test.univ-paris1.fr";
		$url = $eSignature_url.'/ws/signrequests/'.$esignatureid_annule;
		$params = array('id' => $esignatureid_annule);
		$walk = function( $item, $key, $parent_key = '' ) use ( &$output, &$walk ) {
			is_array( $item )
			? array_walk( $item, $walk, $key )
			: $output[] = http_build_query( array( $parent_key ?: $key => $item ) );
			
		};
		array_walk( $params, $walk );
		$json = implode( '&', $output );*/
		$ch = curl_init();
		$idesignature = $this->getIdEsignature();
		curl_setopt($ch, CURLOPT_URL, ESIGNATURE_CURLOPT_URL_GET_SIGNREQ . $idesignature);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$json = curl_exec($ch);
		$result = json_decode($json);
		error_log(basename(__FILE__) . " -- RETOUR ESIGNATURE SUPPRESSION DECREE ".$this->getId()." - id esignature : ".$idesignature."-- " . var_export($result, true));
		$error = curl_error ($ch);
		curl_close($ch);
		$errlog = '';
		if ($error != "")
		{
			elog("Erreur Curl = " . $error);
		}
		if (!is_null($result))
		{
			elog(" Erreur lors de la suppression de l arrete dans Esignature : ".var_export($result, true));
		}
		else
		{
			$this->unsetIdEsignature($userid);
			elog("Demande de signature supprimée pour le decree  ".$this->getId()." par l'utilisateur ".$userid);
		}
	}
}