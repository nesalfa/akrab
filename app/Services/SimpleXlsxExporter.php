<?php

namespace App\Services;

use ZipArchive;

class SimpleXlsxExporter
{
    /**
     * Generate raw binary string dari file .xlsx tanpa dependency vendor eksternal.
     * Menggunakan ZipArchive dan OpenXML Spreadsheet schema standar ECMA-376.
     *
     * @param string $sheetName
     * @param array $headers Header kolom (1D array of string)
     * @param array $rows Baris data (2D array)
     * @param array $meta Informasi tambahan di baris awal (opsional)
     * @return string Binary data .xlsx
     */
    public static function createXlsx(string $sheetName, array $headers, array $rows, array $meta = []): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' .
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' .
            '<Default Extension="xml" ContentType="application/xml"/>' .
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' .
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' .
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' .
            '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' .
            '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' .
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' .
            '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $safeSheetName = htmlspecialchars(mb_substr($sheetName, 0, 31), ENT_XML1, 'UTF-8');
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
            '<sheets>' .
            '<sheet name="' . $safeSheetName . '" sheetId="1" r:id="rId1"/>' .
            '</sheets>' .
            '</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml
        // Custom number formats:
        // 164: +0.00%;-0.00%;0.00%
        // 165: 0.00
        //
        // Fonts:
        // 0: regular 11pt Calibri
        // 1: bold white 11pt Calibri (header)
        // 2: bold purple 11pt Calibri (meta)
        //
        // CellXfs (Styles):
        // 0: normal text (left aligned, thin border)
        // 1: table header (purple bg, bold white text, center aligned)
        // 2: meta label (bold purple text)
        // 3: center text (center aligned, thin border)
        // 4: number 2 decimal (0.00, right aligned, thin border)
        // 5: percent formatted (+0.00%;-0.00%;0.00%, right aligned, thin border)
        // 6: integer number (right aligned, thin border)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' .
            '<numFmts count="2">' .
            '<numFmt numFmtId="164" formatCode="+0.00%;-0.00%;0.00%"/>' .
            '<numFmt numFmtId="165" formatCode="0.00"/>' .
            '</numFmts>' .
            '<fonts count="3">' .
            '<font><sz val="11"/><name val="Calibri"/></font>' .
            '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>' .
            '<font><b/><sz val="11"/><color rgb="FF6A4C93"/><name val="Calibri"/></font>' .
            '</fonts>' .
            '<fills count="4">' .
            '<fill><patternFill patternType="none"/></fill>' .
            '<fill><patternFill patternType="gray125"/></fill>' .
            '<fill><patternFill patternType="solid"><fgColor rgb="FF6A4C93"/></patternFill></fill>' .
            '<fill><patternFill patternType="solid"><fgColor rgb="FFF8F9FA"/></patternFill></fill>' .
            '</fills>' .
            '<borders count="2">' .
            '<border><left/><right/><top/><bottom/><diagonal/></border>' .
            '<border>' .
            '<left style="thin"><color rgb="FFD0D5DD"/></left>' .
            '<right style="thin"><color rgb="FFD0D5DD"/></right>' .
            '<top style="thin"><color rgb="FFD0D5DD"/></top>' .
            '<bottom style="thin"><color rgb="FFD0D5DD"/></bottom>' .
            '</border>' .
            '</borders>' .
            '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>' .
            '<cellXfs count="7">' .
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"><alignment vertical="center"/></xf>' . // 0: text left
            '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>' . // 1: header center
            '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0" applyFont="1"><alignment vertical="center"/></xf>' . // 2: meta label
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>' . // 3: text center
            '<xf numFmtId="165" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>' . // 4: number 2 decimal (0.00)
            '<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>' . // 5: percent (+0.00%;-0.00%;0.00%)
            '<xf numFmtId="1" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment horizontal="right" vertical="center"/></xf>' . // 6: integer
            '</cellXfs>' .
            '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $colCount = max(count($headers), 4);
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';

        // Column widths
        $sheetXml .= '<cols>';
        for ($c = 1; $c <= $colCount; $c++) {
            $w = ($c === 1) ? 8 : (($c === 2 || $c === 3) ? 28 : 22);
            $sheetXml .= '<col min="' . $c . '" max="' . $c . '" width="' . $w . '" customWidth="1"/>';
        }
        $sheetXml .= '</cols>';

        $sheetXml .= '<sheetData>';

        $currentRow = 1;

        // Render Metadata jika ada
        if (!empty($meta)) {
            foreach ($meta as $m) {
                $sheetXml .= '<row r="' . $currentRow . '" ht="20" customHeight="1">';
                if (is_array($m)) {
                    $mLabel = $m[0] ?? '';
                    $mVal = $m[1] ?? '';

                    $sheetXml .= '<c r="A' . $currentRow . '" t="inlineStr" s="2"><is><t>' . self::escapeXml($mLabel) . '</t></is></c>';
                    $sheetXml .= '<c r="B' . $currentRow . '" t="inlineStr" s="0"><is><t>' . self::escapeXml($mVal) . '</t></is></c>';
                } else {
                    $sheetXml .= '<c r="A' . $currentRow . '" t="inlineStr" s="2"><is><t>' . self::escapeXml($m) . '</t></is></c>';
                }
                $sheetXml .= '</row>';
                $currentRow++;
            }
            // Baris kosong pemisah
            $sheetXml .= '<row r="' . $currentRow . '"/>';
            $currentRow++;
        }

        // Row Headers (style 1)
        $sheetXml .= '<row r="' . $currentRow . '" ht="28" customHeight="1">';
        $colIndex = 0;
        foreach ($headers as $header) {
            $colLetter = self::columnIndexToLetter($colIndex);
            $cellRef = $colLetter . $currentRow;
            $val = self::escapeXml((string)$header);
            $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="1"><is><t>' . $val . '</t></is></c>';
            $colIndex++;
        }
        $sheetXml .= '</row>';
        $currentRow++;

        // Rows Data
        foreach ($rows as $row) {
            $sheetXml .= '<row r="' . $currentRow . '" ht="22" customHeight="1">';
            $colIndex = 0;
            foreach ($row as $cellVal) {
                $colLetter = self::columnIndexToLetter($colIndex);
                $cellRef = $colLetter . $currentRow;

                // Cek format khusus jika cell berupa array descriptor: ['type' => 'percent'|'number'|'integer'|'center'|'text', 'value' => ...]
                if (is_array($cellVal) && isset($cellVal['type'])) {
                    $type = $cellVal['type'];
                    $rawVal = $cellVal['value'] ?? null;

                    if ($type === 'percent') {
                        if ($rawVal === null || $rawVal === '-') {
                            $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="3"><is><t>-</t></is></c>';
                        } else {
                            // rawVal disimpan dalam nilai fraksi desimal (misal 0.20 untuk 20%)
                            $sheetXml .= '<c r="' . $cellRef . '" s="5"><v>' . (float)$rawVal . '</v></c>';
                        }
                    } elseif ($type === 'number') {
                        if ($rawVal === null || $rawVal === '-') {
                            $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="3"><is><t>-</t></is></c>';
                        } else {
                            $sheetXml .= '<c r="' . $cellRef . '" s="4"><v>' . (float)$rawVal . '</v></c>';
                        }
                    } elseif ($type === 'integer') {
                        if ($rawVal === null || $rawVal === '-') {
                            $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="3"><is><t>-</t></is></c>';
                        } else {
                            $sheetXml .= '<c r="' . $cellRef . '" s="6"><v>' . (int)$rawVal . '</v></c>';
                        }
                    } elseif ($type === 'center') {
                        $clean = self::escapeXml((string)($rawVal ?? '-'));
                        $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="3"><is><t>' . $clean . '</t></is></c>';
                    } else {
                        $clean = self::escapeXml((string)($rawVal ?? ''));
                        $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="0"><is><t>' . $clean . '</t></is></c>';
                    }
                } elseif (is_int($cellVal)) {
                    $styleId = ($colIndex === 0) ? '3' : '6'; // center for No. column
                    $sheetXml .= '<c r="' . $cellRef . '" s="' . $styleId . '"><v>' . $cellVal . '</v></c>';
                } elseif (is_float($cellVal)) {
                    $sheetXml .= '<c r="' . $cellRef . '" s="4"><v>' . $cellVal . '</v></c>';
                } elseif ($cellVal === '-') {
                    $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="3"><is><t>-</t></is></c>';
                } else {
                    $cleanVal = self::sanitizeFormula((string)($cellVal ?? ''));
                    $escaped = self::escapeXml($cleanVal);
                    $styleId = ($colIndex === 0) ? '3' : '0';
                    $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="' . $styleId . '"><is><t>' . $escaped . '</t></is></c>';
                }
                $colIndex++;
            }
            $sheetXml .= '</row>';
            $currentRow++;
        }

        $sheetXml .= '</sheetData></worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();

        $binary = file_get_contents($tempFile);
        @unlink($tempFile);

        return $binary;
    }

    private static function sanitizeFormula(string $str): string
    {
        // Hanya tambahkan kutip tunggal jika merupakan formula formula berbahaya excel (=, @)
        // Jangan tambahkan kutip jika itu bukan diawali = atau @
        if (preg_match('/^[=@]/', $str)) {
            return "'" . $str;
        }
        return $str;
    }

    private static function escapeXml(string $str): string
    {
        return htmlspecialchars($str, ENT_XML1, 'UTF-8');
    }

    private static function columnIndexToLetter(int $index): string
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr($index % 26 + 65) . $letter;
            $index = intdiv($index, 26) - 1;
        }
        return $letter;
    }
}
