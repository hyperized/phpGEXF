<?php

	class phpGEXF
	{
		private $includeViz = true;
		private $domVersion = '1.0';
		private $gexfVersion = '1.2draft';
		private $characterEncoding = 'UTF-8';
		private $lastModifiedDataFormat = 'Y-m-d';

		private $xml;
		private $gexf;
		private $meta;
		private $graph;
		private $nodes;
		private $edges;

		private $allowedMetadata;
		private $allowedNodeShapeArray;
		private $allowedEdgeShapeArray;
		private $allowedEdgeTypeArray;
		private $allowedModeArray;

		public function __construct()
		{
			$this->init();
		}
		public function __destruct() {}

		public function __toString()
		{
			return $this->xml->saveXML();
		}

		private function init()
		{
			$this->constructDom();
			$this->constructNamespace();
			$this->constructMetadata();
			$this->constructElements();
		}

		// Create basic DOM template
		private function constructDom()
		{
			$this->xml = new DomDocument($this->domVersion, $this->characterEncoding);
			$this->xml->formatOutput = true;
			$this->gexf = $this->xml->createElementNS(null, 'gexf'); // Create the tree!
			$this->gexf = $this->xml->appendChild($this->gexf);
		}

		private function constructNamespace()
		{
			if($this->includeViz)
			{
				$this->gexf->setAttribute('xmlns:viz', 'http://www.gexf.net/'.$this->gexfVersion.'/viz');
			}

			$this->gexf->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
			$this->gexf->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://www.gexf.net/'.$this->gexfVersion.' http://www.gexf.net/'.$this->gexfVersion.'/gexf.xsd');
		}

		private function constructMetadata()
		{
			$this->meta = $this->gexf->appendChild($this->xml->createElement('meta'));
			$this->meta->setAttribute('lastmodifieddate', date($this->lastModifiedDataFormat));
		}

		private function constructElements()
		{
			$this->graph = $this->gexf->appendChild($this->xml->createElement('graph'));
			$this->nodes = $this->graph->appendChild($this->xml->createElement('nodes'));
			$this->edges = $this->graph->appendChild($this->xml->createElement('edges'));
		}

		public function addMetaData($field, $description)
		{
			if(!is_array($this->allowedMetadata))
			{
				$this->setAllowedMetadataArray();
			}

			if (in_array($field,$this->allowedMetadata ))
			{
				if(is_string($field) && is_string($description))
				{
					$this->meta->appendChild($this->xml->createElement($field, $description));
					return true;
				}
				else
				{
					return false;
				}
			}
		}

		public function addNode($id, $label)
		{
			if( ( is_int($id) || is_string($id) ) && is_string($label) ) // In Hierarchy, the node ID can be a string too!
			{
				$this->node = $this->xml->createElement('node');
				$this->node->setAttribute('id', $id); // integer
				$this->node->setAttribute('label', $label); // string
				$this->nodes->appendChild($this->node);
				return $id;
			}
			else
			{
				return false;
			}
		}


		public function addEdge($source, $target) {}

		public function setEdgeType($type) {} // string, allowedEdgeTypeArray[]
		public function setEdgeMode($mode) {} // string, allowedModeArray[]
		public function setEdgeWeight($weight) {} // float

		// VIZ http://gexf.net/1.2draft/viz.xsd
		public function Color($r, $g, $b, $alpha, $start, $startopen, $end, $endopen) {} // RGB req, alpha
		public function Position($x, $y, $z, $start, $startopen, $end, $endopen) {}
		public function Size($value, $start, $startopen, $end, $endopen) {}
		public function Thickness($value, $start, $startopen, $end, $endopen) {}
		public function NodeShape($value, $uri, $start, $startopen, $end, $endopen) {}
		public function EdgeShape($value, $start, $startopen, $end, $endopen) {}
		public function ColorChannel($value); // 0 - 255 range
		public function AlphaChannel($value); // float between 0.0 and 1.0
		public function SizeType($value); // float
		public function SpacePoint($value); // float
		
		// Set value range Arrays based on spec // http://gexf.net/1.2draft/gexf.xsd
		private function setAllowedMetadataArray()
		{
			$this->allowedMetadata = array(
				'creator',
				'keywords',
				'description',
			);
		}

		private function setAllowedNodeShapeArray()
		{
			$this->allowedNodeShapeArray = array (
				'disc',
				'square',
				'triangle',
				'diamond',
				'image',
			);
		}

		private function setAllowedEdgeShapeArray()
		{
			$this->allowedEdgeShapeArray = array (
				'solid',
				'dotted',
				'dashed',
				'double',
			);
		}

		private function setAllowedEdgeTypeArray()
		{
			$this->allowedEdgeTypeArray = array (
				'directed',
				'undirected',
				'mutual'
			);
		}

		private function setAllowedModeArray()
		{
			$this->allowedModeArray = array (
				'static',
				'dynamic',
			);
		}
	}

	class Node {} // Keep open the possibility to split into objects
	class Edge {}

?>