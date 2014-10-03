<?php

	class phpGEXF
	{
		private $includeViz = true;
		private $gexfVersion = '1.0';
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
			$this->xml = new DomDocument($this->$gexfVersion, $this->$characterEncoding);
			$this->xml->formatOutput = true;
			$this->gexf = $this->xml->createElementNS(null, 'gexf');
			$this->gexf = $this->xml->appendChild($this->gexf);
		}

		private function constructNamespace()
		{
			if($this->includeViz)
			{
				$this->gexf->setAttribute('xmlns:viz', 'http://www.gexf.net/1.2draft/viz');
			}

			$this->gexf->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
			$this->gexf->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://www.gexf.net/1.2draft http://www.gexf.net/1.2draft/gexf.xsd');
		}

		private function constructMetadata()
		{
			$this->meta = $this->gexf->appendChild($this->xml->createElement('meta'));
			$this->meta->setAttribute('lastmodifieddate', date($this->lastModifiedDataFormat));
		}

		private function setAllowedMetadataArray()
		{
			$this->allowedMetadata = array(
				'creator',
				'keywords',
				'description',
			);
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
				$this->meta->appendChild($this->xml->createElement($field, $description));
			}
		}

		public function addNode($id, $label)
		{
			$node = $xml->createElement('node');
			//$node->setAttribute('id', '1');
			//$node->setAttribute('label', 'Hello world!');
			$nodes->appendChild($node);
		}
		public function addEdge($source, $target) {}

		// VIZ http://gexf.net/1.2draft/viz.xsd
		public function addColor($r, $g, $b, $alpha, $start, $startopen, $end, $endopen) {} // RGB req, a
		public function addPosition($x, $y, $z, $start, $startopen, $end, $endopen) {}
		public function addSize($value, $start, $startopen, $end, $endopen) {}
		public function addThickness($value, $start, $startopen, $end, $endopen) {}
		public function addNodeShape($value, $uri, $start, $startopen, $end, $endopen) {}
		public function addEdgeShape($value, $start, $startopen, $end, $endopen) {}
		public function addColorChannel($value); // 0 - 255 range
		public function addAlphaChannel($value); // float between 0.0 and 1.0
		public function addSizeType($value); // float
		public function addSpacePoint($value); // float
		
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
			$this->allowedEdgeShapeArray = array(
				'solid',
				'dotted',
				'dashed',
				'double'
			)
		}
	}

?>