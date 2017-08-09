<?php


namespace NetteEmailOnAcid\Tracy;


use Tracy\Dumper;
use Tracy\IBarPanel;

class EmailOnAcidPanel implements IBarPanel
{
	/**
	 * @var Log[]
	 */
	private $data = [];

	/**
	 * @var bool
	 */
	private $haveError = false;

	function getTab(): string
	{
		return '<div '.($this->haveError ? 'style="background:#f77e84"':'').'>
					<img style="height:15px" src=\'data:image/png;base64,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\'/>
					Email On Acid (' . count($this->data) . ')
				</div>';
	}

	function getPanel(): string
	{
		if (empty($this->data)) {
			return '';
		}
		return '<h1>Api calls</h1>
				<div class="tracy-inner">' . $this->renderTable() . '</div>';
	}

	private function renderTable(): string
	{
		return sprintf(
			'<table>
					<tr>
						<td>URL</td>
						<td>Parameters</td>
						<td>Result</td>
					</tr>
					%s
				</table>',
			$this->renderRows()
		);
	}

	private function renderRows(): string
	{
		$rows = '';
		foreach ($this->data as $log) {
			$rows .= $this->renderRow($log);
		}
		return $rows;
	}

	/**
	 * @param Log $log
	 * @return string
	 */
	private function renderRow(Log $log): string
	{
		return sprintf(
			'<tr>
						<td>%s <br/> %s</td>
						<td>%s</td>
						<td>%s</td>
					</tr>',
			$log->getUrl(),
			$log->getHttpMethod(),
			Dumper::toHtml($log->getRequestBody()),
			Dumper::toHtml($log->getResponseBody())
		);
	}

	/**
	 * @param Log $log
	 */
	public function addData(Log $log)
	{
		if($log->isError()){
			$this->haveError = true;
		}
		if (\Tracy\Debugger::$productionMode) {
			return;
		}
		$this->data[] = $log;
	}


}