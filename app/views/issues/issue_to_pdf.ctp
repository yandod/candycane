<?php
$tcpdf->SetTitle($issue['Project']['name'].' - #'.$issue['Tracker']['name'].' '.$issue['Issue']['id']);
$tcpdf->AliasNbPages();
$tcpdf->footer_date = $candy->format_date(date('Y-m-d'));
$tcpdf->AddPage();

$tcpdf->SetFontStyle('B',11);
$tcpdf->Cell(190,10, $issue['Project']['name'].' - '.$issue['Tracker']['name'].' # '.$issue['Issue']['id'].': '.$issue['Issue']['subject']);
$tcpdf->Ln();

$y0 = $tcpdf->GetY();

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Status',true).":","LT");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $issue['Status']['name'],"RT");
$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Priority',true).":","LT");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $issue['Priority']['name'],"RT");        
$tcpdf->Ln();

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Author',true).":","L");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $candy->format_username($issue['Author']),"R");
$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Category',true).":","L");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $issue['Category']['name'],"R");
$tcpdf->Ln();

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Created',true).":","L");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $candy->format_date($issue['Issue']['created_on']),"R");
$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Assigned to',true).":","L");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $candy->format_username($issue['AssignedTo']),"R");
$tcpdf->Ln();

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Updated',true).":","LB");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $candy->format_date($issue['Issue']['updated_on']),"RB");
$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Due date',true).":","LB");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(60,5, $candy->format_date($issue['Issue']['due_date']),"RB");
$tcpdf->Ln();

if(!empty($issue['CustomValue'])) {
  foreach($issue['CustomValue'] as $value) {
    $tcpdf->SetFontStyle('B',9);
    $tcpdf->Cell(35,5, $value['CustomField']['name'].":","L");
    $tcpdf->SetFontStyle('',9);
    $tcpdf->MultiCell(155,5, $customField->value($value),"R");
  }
}

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Subject',true).":","LTB");
$tcpdf->SetFontStyle('',9);
$tcpdf->Cell(155,5, $issue['Issue']['subject'],"RTB");
$tcpdf->Ln();

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(35,5, __('Description',true).":");
$tcpdf->SetFontStyle('',9);
$tcpdf->MultiCell(155,5, $issue['Issue']['description'],"BR");

$tcpdf->Line($tcpdf->GetX(), $y0, $tcpdf->GetX(), $tcpdf->GetY());
$tcpdf->Line($tcpdf->GetX(), $tcpdf->GetY(), 170, $tcpdf->GetY());
$tcpdf->Ln();

if(!empty($issue['Changeset']) && $candy->authorize_for(':view_changesets', $issue)) {
  $tcpdf->SetFontStyle('B',9);
  $tcpdf->Cell(190,5, __('Associated revisions',true), "B");
  $tcpdf->Ln();
  foreach($issue['Changeset'] as $changeset) {
    $tcpdf->SetFontStyle('B',8);
    $tcpdf->Cell(190,5, $candy->format_time($changeset['Changeset']['committed_on'])." - ".$candy->format_username($changeset['Author']));
    $tcpdf->Ln();
    if(strlen($changeset['Changeset']['comments'])) {
      $tcpdf->SetFontStyle('',8);
      $tcpdf->MultiCell(190,5, $changeset['Changeset']['comments']);
    }
    $tcpdf->Ln();
  }
}

$tcpdf->SetFontStyle('B',9);
$tcpdf->Cell(190,5, __('History',true), "B");
$tcpdf->Ln();
foreach($journalList as $journal) {
  $tcpdf->SetFontStyle('B',8);
  $tcpdf->Cell(190,5, $candy->format_time($journal['Journal']['created_on'])." - ".$candy->format_username($journal['User']));
  $tcpdf->Ln();
  $tcpdf->SetFontStyle('I',8);
  foreach($journal['JournalDetail'] as $detail) {
    $tcpdf->Cell(190,5, "- ".$issues->show_detail($detail, true));
    $tcpdf->Ln();
  }
  if(!empty($journal['Journal']['notes'])) {
    $tcpdf->SetFontStyle('',8);
    $tcpdf->MultiCell(190,5, $journal['Journal']['notes']);
  }
  $tcpdf->Ln();
}

if(!empty($attachments)) {
  $tcpdf->SetFontStyle('B',9);
  $tcpdf->Cell(190,5, __('Files',true), "B");
  $tcpdf->Ln();
  foreach($attachments as $attachment) {
    $tcpdf->SetFontStyle('',8);
    $tcpdf->Cell(80,5, $attachment['Attachment']['filename']);
    $tcpdf->Cell(20,5, $number->toReadableSize($attachment['Attachment']['filesize']),0,0,"R");
    $tcpdf->Cell(25,5, $candy->format_date($attachment['Attachment']['created_on']),0,0,"R");
    $tcpdf->Cell(65,5, $candy->format_username($attachment['Author']),0,0,"R");
    $tcpdf->Ln();
  }
}
$tcpdf->Output($main_project['Project']['identifier'].'-'.$issue['Issue']['id'].'.pdf', "D");
?>