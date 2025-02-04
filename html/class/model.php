<?php
require_once './include/const.php';
require_once './include/fonctions.php';

class model {
	
	private $_dbcon;
	
	private $_idmodel;
	
	private $_iddecree_type;
	
	private $_name;
	
	private $_model_path;
	
	private $_export_path;
	
	function __construct($dbcon, $idmodel)
	{
		require_once ("./include/dbconnection.php");
		$this->_idmodel = intval($idmodel);
		$this->_dbcon = $dbcon;
	}
	
	function getid()
	{
		return $this->_idmodel;
	}
	
	
	function getModelInfo()
	{
		$select = "SELECT model.*, dty.name as namedecree_type FROM model INNER JOIN decree_type dty ON dty.iddecree_type = model.iddecree_type WHERE model.idmodel = ?";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				return $res;
			}
			else
			{
				elog("model $this->_idmodel absent de la table.");
			}
		}
		else
		{
			elog("erreur select * from model $this->_idmodel ".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function getDecreeType()
	{
		$select = "SELECT dty.* FROM model mod INNER JOIN decree_type dty ON dtY.iddecree_type = mod.iddecree_type WHERE mod.idmodel = ?";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				return $res;
			}
			else
			{
				elog("decreetype pour le model $this->_idmodel absent de la base.");
			}
		}
		else
		{
			elog("erreur select decreetype for model. ".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function getfile()
	{
		$select = "SELECT model_path FROM model WHERE idmodel = ?";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				return $res['model_path'];
			}
			else 
			{
				elog("model $this->_idmodel absent de la table.");
			}
		}
		else 
		{
			elog("erreur select model_path from model. ".mysqli_error($this->_dbcon));
		}
		return 0;
	}
	
	function getModelFields()
	{
		$select = "SELECT mfi.idmodel_field, mfi.number, mfi.auto, mfi.auto_value, mfi.linkedto, mfi.complement_after, fty.* FROM model_field mfi INNER JOIN field_type fty ON mfi.idfield_type = fty.idfield_type WHERE mfi.idmodel = ? ORDER BY mfi.order";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		$fields = array();
		if ( !mysqli_error($this->_dbcon))
		{
			while ($res = mysqli_fetch_assoc($result))
			{
				$fields[] = $res;
			}
		}
		else
		{
			elog("erreur select fields from model. ".mysqli_error($this->_dbcon));
		}
		return $fields;
	}
	
	function getQueryField($field_type)
	{
		$select = "SELECT qfi.schema, qfi.query, qmf.query_clause FROM query_field qfi LEFT JOIN query_model_field qmf ON qmf.idquery_field = qfi.idquery_field  AND qmf.idmodel = ? WHERE qfi.idfield_type = ?";
		$params = array($this->_idmodel, $field_type);
		$result = prepared_select($this->_dbcon, $select, $params);
		$fields = array();
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$fields = $res;
			}
		}
		else
		{
			elog("erreur select query_model_fields. ".mysqli_error($this->_dbcon));
		}
		return $fields;
	}
	
	function getListField($field_type)
	{
		$select = "SELECT idlist_field, value FROM list_field WHERE idfield_type = ?";
		$params = array($field_type);
		$result = prepared_select($this->_dbcon, $select, $params);
		$fields = array();
		if ( !mysqli_error($this->_dbcon))
		{
			while ($res = mysqli_fetch_assoc($result))
			{
				$fields[] = array('key' => $res['idlist_field'], 'value' => $res['value']);
			}
		}
		else
		{
			elog("erreur select list_fields. ".mysqli_error($this->_dbcon));
		}
		return $fields;
	}

	function getNumeroId()
	{
		$select = "SELECT idmodel_field FROM model_field WHERE idmodel = ? AND idfield_type = 1";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		$numeroid = 0;
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_row($result))
			{
				$numeroid = $res[0];
			}
		}
		else
		{
			elog("erreur select fields from model. ".mysqli_error($this->_dbcon));
		}
		return $numeroid;		
	}
	
	function getInfofield($idmodel_field)
	{
		$select = "SELECT mfi.idmodel_field, mfi.number, mfi.auto, mfi.auto_value, fty.* FROM model_field mfi INNER JOIN field_type fty ON mfi.idfield_type = fty.idfield_type WHERE mfi.idmodel = ? AND mfi.idmodel_field = ?";
		$params = array($this->_idmodel, $idmodel_field);
		$result = prepared_select($this->_dbcon, $select, $params);
		$infos = array();
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$infos = $res;
			}
		}
		else
		{
			elog("erreur select fields from model. ".mysqli_error($this->_dbcon));
		}
		return $infos;
	}

	function getExportPath()
	{
		if (isset($this->_export_path))
		{
			return $this->_export_path;
		}
		$select = 'SELECT export_path FROM model WHERE idmodel = ?';
		$param = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $param);
		$export_path = NULL;
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$export_path = $res['export_path'];
			}
		}
		else
		{
			elog("erreur select export_path from model. ".mysqli_error($this->_dbcon));
		}
		return $export_path;
	}

	function getWorkflow()
	{
		$select = 'SELECT idworkflow_esign FROM model_workflow WHERE idmodel = ?';
		$param = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $param);
		$id = NULL;
		if ( !mysqli_error($this->_dbcon))
		{
			if ($res = mysqli_fetch_assoc($result))
			{
				$id = $res['idworkflow_esign'];
			}
			else
			{
				elog('Workflow eSignature non renseigné.');
			}
		}
		else
		{
			elog("Erreur select idworkflow_esign from model_workflow. ".mysqli_error($this->_dbcon));
		}
		return $id;
	}

	function getFieldsForFileName()
	{
		$select = "SELECT idmodel_field, filename_position FROM model_field WHERE idmodel = ? AND filename_position IS NOT NULL AND filename_position > 0 ORDER BY filename_position";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		$fields = array();
		if ( !mysqli_error($this->_dbcon))
		{
			while ($res = mysqli_fetch_assoc($result))
			{
				$fields[] = $res;
			}
		}
		else
		{
			elog("erreur select fields from model. ".mysqli_error($this->_dbcon));
		}
		return $fields;
	}

	function isActive()
	{
		$select = "SELECT active FROM model WHERE idmodel = ? AND active = 'O'";
		$params = array($this->_idmodel);
		$result = prepared_select($this->_dbcon, $select, $params);
		if ( !mysqli_error($this->_dbcon))
		{
			if (mysqli_num_rows($result) > 0)
			{
				return true;
			}
		}
		else
		{
			elog("erreur select active from model. ".mysqli_error($this->_dbcon));
		}
		return false;
	}
}