<?php
Configure::write('debug', 0);
$this->Tcpdf->SetTitle($issue['Project']['name'].' - #'.$issue['Tracker']['name'].' '.$issue['Issue']['id']);
$this->Tcpdf->AliasNbPages();
$this->Tcpdf->footer_date = $this->Candy->format_date(date('Y-m-d'));
$this->Tcpdf->AddPage();

$this->Tcpdf->SetFontStyle('B',11);
$this->Tcpdf->Cell(190,10, $issue['Project']['name'].' - '.$issue['Tracker']['name'].' # '.$issue['Issue']['id'].': '.$issue['Issue']['subject']);
$this->Tcpdf->Ln();

$y0 = $this->Tcpdf->GetY();

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Status').":","LT");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $issue['Status']['name'],"RT");
$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Priority').":","LT");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $issue['Priority']['name'],"RT");        
$this->Tcpdf->Ln();

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Author').":","L");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $this->Candy->format_username($issue['Author']),"R");
$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Category').":","L");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $issue['Category']['name'],"R");
$this->Tcpdf->Ln();

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Created').":","L");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $this->Candy->format_date($issue['Issue']['created_on']),"R");
$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Assigned to').":","L");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $this->Candy->format_username($issue['AssignedTo']),"R");
$this->Tcpdf->Ln();

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Updated').":","LB");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $this->Candy->format_date($issue['Issue']['updated_on']),"RB");
$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Due date').":","LB");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(60,5, $this->Candy->format_date($issue['Issue']['due_date']),"RB");
$this->Tcpdf->Ln();

if(!empty($issue['CustomValue'])) {
  foreach($issue['CustomValue'] as $value) {
    $this->Tcpdf->SetFontStyle('B',9);
    $this->Tcpdf->Cell(35,5, $value['CustomField']['name'].":","L");
    $this->Tcpdf->SetFontStyle('',9);
    $this->Tcpdf->MultiCell(155,5, $this->CustomField->value($value),"R");
  }
}

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Subject').":","LTB");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->Cell(155,5, $issue['Issue']['subject'],"RTB");
$this->Tcpdf->Ln();

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(35,5, __('Description').":");
$this->Tcpdf->SetFontStyle('',9);
$this->Tcpdf->MultiCell(155,5, $issue['Issue']['description'],"BR");

$this->Tcpdf->Line($this->Tcpdf->GetX(), $y0, $this->Tcpdf->GetX(), $this->Tcpdf->GetY());
$this->Tcpdf->Line($this->Tcpdf->GetX(), $this->Tcpdf->GetY(), 170, $this->Tcpdf->GetY());
$this->Tcpdf->Ln();

if(!empty($issue['Changeset']) && $this->Candy->authorize_for(':view_changesets', $issue)) {
  $this->Tcpdf->SetFontStyle('B',9);
  $this->Tcpdf->Cell(190,5, __('Associated revisions'), "B");
  $this->Tcpdf->Ln();
  foreach($issue['Changeset'] as $changeset) {
    $this->Tcpdf->SetFontStyle('B',8);
    $this->Tcpdf->Cell(190,5, $this->Candy->format_time($changeset['Changeset']['committed_on'])." - ".$this->Candy->format_username($changeset['Author']));
    $this->Tcpdf->Ln();
    if(strlen($changeset['Changeset']['comments'])) {
      $this->Tcpdf->SetFontStyle('',8);
      $this->Tcpdf->MultiCell(190,5, $changeset['Changeset']['comments']);
    }
    $this->Tcpdf->Ln();
  }
}

$this->Tcpdf->SetFontStyle('B',9);
$this->Tcpdf->Cell(190,5, __('History'), "B");
$this->Tcpdf->Ln();
if (!isset($journalList) || !is_array($journalList)) {
    $journalList = array();
}
foreach($journalList as $journal) {
  $this->Tcpdf->SetFontStyle('B',8);
  $this->Tcpdf->Cell(190,5, $this->Candy->format_time($journal['Journal']['created_on'])." - ".$this->Candy->format_username($journal['User']));
  $this->Tcpdf->Ln();
  $this->Tcpdf->SetFontStyle('I',8);
  foreach($journal['JournalDetail'] as $detail) {
    $this->Tcpdf->Cell(190,5, "- ".$this->Issues->show_detail($detail, true));
    $this->Tcpdf->Ln();
  }
  if(!empty($journal['Journal']['notes'])) {
    $this->Tcpdf->SetFontStyle('',8);
    $this->Tcpdf->MultiCell(190,5, $journal['Journal']['notes']);
  }
  $this->Tcpdf->Ln();
}


if(!empty($attachments)) {
  $this->Tcpdf->SetFontStyle('B',9);
  $this->Tcpdf->Cell(190,5, __('Files'), "B");
  $this->Tcpdf->Ln();
  foreach($attachments as $attachment) {
    $this->Tcpdf->SetFontStyle('',8);
    $this->Tcpdf->Cell(80,5, $attachment['Attachment']['filename']);
    $this->Tcpdf->Cell(20,5, $this->Number->toReadableSize($attachment['Attachment']['filesize']),0,0,"R");
    $this->Tcpdf->Cell(25,5, $this->Candy->format_date($attachment['Attachment']['created_on']),0,0,"R");
    $this->Tcpdf->Cell(65,5, $this->Candy->format_username($attachment['Author']),0,0,"R");
    $this->Tcpdf->Ln();
  }
}
$this->Tcpdf->Output($main_project['Project']['identifier'].'-'.$issue['Issue']['id'].'.pdf', "D");
