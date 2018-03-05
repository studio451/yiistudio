<?

namespace admin\modules\shopcart\export;

use Yii;
use yii\base\Model;
use admin\modules\shopcart\models\Good;
use admin\models\Setting;

class OrderToExcel extends Model {

    private static function _sheetHeader($sheet, $row, $text1, $text2) {
        $sb = array('borders' => array(
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)
        ));

        $sheet->getRowDimension($row)->setRowHeight(10);
        $sheet->getStyle($row)->getFont()->setSize(8);
        $sheet->getStyle('A' . $row)->applyFromArray($sb);
        $sheet->getStyle('E' . $row)->applyFromArray($sb);

        $sheet->setCellValueByColumnAndRow(1, $row, $text1);
        $sheet->setCellValueByColumnAndRow(2, $row, "__.__.___");
        $sheet->mergeCells('F' . $row . ':G' . $row);
        $sheet->setCellValueByColumnAndRow(5, $row, $text2);
        $sheet->setCellValueByColumnAndRow(7, $row, "__.__.___");
    }

    public function export($order) {

        $excel = new \PHPExcel();
        $sheet = $excel->getActiveSheet();

        $sb = ['borders' => [
                'top' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'bottom' => ['style' => \PHPExcel_Style_Border::BORDER_THIN],
                'left' => ['style' => \PHPExcel_Style_Border::BORDER_THIN]
        ]];

        $row = 1;
        $sheet->mergeCells('A' . $row . ':H' . $row);
        $sheet->setCellValueByColumnAndRow(0, $row, "ЗАКАЗ № " . $order->id . " от " . Yii::$app->formatter->asDatetime($order->time, 'medium'));
        $sheet->getStyle('A' . $row)->getFont()->setSize(13);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);


//        if (in_array($arOrder["USER_ID"], CGroup::GetGroupUser(CEsumki::__getWhoresaleGroup($arOrder["LID"])))) {
//            $sheet->mergeCells('I' . $row . ':L' . $row);
//            $sheet->setCellValueByColumnAndRow(8, $row, iconv('windows-1251', 'utf-8', "УПАКОВАТЬ В МЕШОК С ПЛОМБОЙ!"));
//            $sheet->getStyle('I' . $row)->getFont()->setSize(9);
//            $sheet->getStyle('I' . $row)->getFont()->setBold(true);
//            $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//        }

        $sheet->getColumnDimension("A")->setWidth(4);
        $sheet->getColumnDimension("B")->setWidth(16);
        $sheet->getColumnDimension("C")->setWidth(8);
        $sheet->getColumnDimension("D")->setWidth(2);
        $sheet->getColumnDimension("E")->setWidth(4);
        $sheet->getColumnDimension("F")->setWidth(11);
        $sheet->getColumnDimension("G")->setWidth(5);
        $sheet->getColumnDimension("H")->setWidth(8);
        $sheet->getColumnDimension("I")->setWidth(4);
        $sheet->getColumnDimension("J")->setWidth(6);
        $sheet->getColumnDimension("K")->setWidth(10);
        $sheet->getColumnDimension("L")->setWidth(10);

        $row++;
        self::_sheetHeader($sheet, $row, "счет отправлен", "заказан водитель");
        $row++;
        self::_sheetHeader($sheet, $row, "оплачен аванс", "заявка в ТК");
        $row++;
        self::_sheetHeader($sheet, $row, "счет оплачен", "товар отгружен");
        $row++;
        self::_sheetHeader($sheet, $row, "заказ собран, мест(__)", "груз передан в ТК(ТТН)");
        $row++;
        self::_sheetHeader($sheet, $row, "документы отданы", "");
        $row++;

        $row++;
        $sheet->mergeCells('A' . $row . ':L' . $row);
        $sheet->setCellValueByColumnAndRow(0, $row, "ПОКУПАТЕЛЬ: " . $order->name);
        $sheet->getStyle('A' . $row)->getFont()->setSize(10);
        $row++;
        $sheet->mergeCells('A' . $row . ':L' . $row);
        $sheet->setCellValueByColumnAndRow(0, $row, "E-mail: " . $order->email);
        $sheet->getStyle('A' . $row)->getFont()->setSize(10);
        $row++;
        $sheet->mergeCells('A' . $row . ':L' . $row);
        $sheet->setCellValueByColumnAndRow(0, $row, "Телефон: " . $order->phone);
        $sheet->getStyle('A' . $row)->getFont()->setSize(10);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        if ($order->address != "") {
            $sheet->getRowDimension($row)->setRowHeight(27);
            $sheet->setCellValueByColumnAndRow(0, $row, "Адрес: " . $order->address);
            $sheet->mergeCells('A' . $row . ':L' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setSize(10);
            $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $row++;
        }
        if ($order->comment != "") {
            $sheet->getRowDimension($row)->setRowHeight(27);
            $sheet->setCellValueByColumnAndRow(0, $row, "Комментарий: " . $order->comment);
            $sheet->mergeCells('A' . $row . ':L' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setSize(10);
            $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
            $row++;
        }
        $sheet->setCellValueByColumnAndRow(0, $row, "№");
        $sheet->getStyle('A' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffdddddd"));
        $sheet->getStyle('A' . $row)->applyFromArray($sb);
        $sheet->getStyle('A' . $row)->getFont()->setSize(10);

        $sheet->mergeCells('B' . $row . ':F' . $row);
        $sheet->setCellValueByColumnAndRow(1, $row, "Название");
        $sheet->getStyle('B' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('B' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffdddddd"));
        $sheet->getStyle('B' . $row . ':F' . $row)->applyFromArray($sb);
        $sheet->getStyle('B' . $row)->getFont()->setSize(10);

        $sheet->mergeCells('G' . $row . ':I' . $row);
        $sheet->setCellValueByColumnAndRow(6, $row, "Артикул");
        $sheet->getStyle('G' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('G' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffdddddd"));
        $sheet->getStyle('G' . $row . ':I' . $row)->applyFromArray($sb);
        $sheet->getStyle('G' . $row)->getFont()->setSize(10);

        $sheet->setCellValueByColumnAndRow(9, $row, "Кол-во");
        $sheet->getStyle('J' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('J' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffdddddd"));
        $sheet->getStyle('J' . $row)->applyFromArray($sb);
        $sheet->getStyle('J' . $row)->getFont()->setSize(10);

        $sheet->setCellValueByColumnAndRow(10, $row, "Цена");
        $sheet->getStyle('K' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('K' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffdddddd"));
        $sheet->getStyle('K' . $row)->applyFromArray($sb);
        $sheet->getStyle('K' . $row)->getFont()->setSize(10);

        $sheet->setCellValueByColumnAndRow(11, $row, "Сумма");
        $sheet->getStyle('L' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('L' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffdddddd"));
        $sheet->getStyle('L' . $row)->applyFromArray($sb);
        $sheet->getStyle('L' . $row)->getFont()->setSize(10);

        $start_row = $row;
        $goods = Good::find()->where(['order_id' => $order->primaryKey])->with('item')->asc()->all();
        $goods_total_count = 0;
        foreach ($goods as $good) {
            $goods_total_count += $good->count;

            $good->item->title;
            $good->options;
            $good->count;
            $good->discount;

            

            $row++;

            $sheet->getRowDimension($row)->setRowHeight(27);

            $sheet->getCell('A' . $row)->setValue('=ROW(D' . $row . ')-' . $start_row);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A' . $row)->getFont()->setSize(10);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('A' . $row)->applyFromArray($sb);

            $sheet->mergeCells('B' . $row . ':F' . $row);
            $sheet->setCellValueByColumnAndRow(1, $row, $good->item->title);
            $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('B' . $row)->getFont()->setSize(10);
            $sheet->getStyle('B' . $row . ':F' . $row)->applyFromArray($sb);

            $sheet->mergeCells('G' . $row . ':I' . $row);
            $sheet->setCellValueByColumnAndRow(6, $row, $good->item->article);
            $sheet->getStyle('G' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('G' . $row)->getFont()->setSize(10);
            $sheet->getStyle('G' . $row . ':I' . $row)->applyFromArray($sb);

            $sheet->getCell('J' . $row)->setValueExplicit($good->count, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->getStyle('J' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('J' . $row)->getFont()->setSize(10);
            $sheet->getStyle('J' . $row)->applyFromArray($sb);

            if ($good->discount) {
                $price = round($good->price * (1 - $good->discount / 100));
            } else {
                $price = $good->price;
            }
            
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $sheet->getCell('K' . $row)->setValueExplicit($price, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->getStyle('K' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('K' . $row)->getFont()->setSize(10);
            $sheet->getStyle('K' . $row)->applyFromArray($sb);

            $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $sheet->getCell('L' . $row)->setValue('=PRODUCT(J' . $row . ':K' . $row . ')');
            $sheet->getStyle('L' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('L' . $row)->getFont()->setSize(10);
            $sheet->getStyle('L' . $row)->applyFromArray($sb);
        }

        $row++;
        $sheet->setCellValueByColumnAndRow(0, $row, "ИТОГО:");

        $sheet->mergeCells('A' . $row . ':' . 'K' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffffff77"));
        $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray($sb);
        
        $sheet->getStyle('L' . $row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('L' . $row)->getFill()->setStartColor(new \PHPExcel_Style_Color("ffffff77"));
        $sheet->getStyle('L' . $row)->getFont()->setBold(true);        
        $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        $sheet->getCell('L' . $row)->setValue('=SUM(L' . ($start_row + 1) . ':L' . ($row - 1) . ')');
        $sheet->getStyle('L' . $row)->applyFromArray($sb);
        
        $row++;
        $row++;
        
        $sheet->mergeCells('A' . $row . ':L' . $row);
        $sheet->setCellValueByColumnAndRow(0, $row, Setting::get('contact_url') . ", т." . Setting::get('contact_telephone'). ", email: " . Setting::get('contact_email') . ", " . Setting::get('contact_addressLocality') . ", " . Setting::get('contact_streetAddress'));
        $sheet->getStyle('A' . $row)->getFont()->setSize(8);

        // Выводим HTTP-заголовки
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_order_' . $order->id . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Выводим содержимое файла
        $objWriter = new \PHPExcel_Writer_Excel2007($excel);
        $objWriter->save('php://output');
        exit();
    }

}
