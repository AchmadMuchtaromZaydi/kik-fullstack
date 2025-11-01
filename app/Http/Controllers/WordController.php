<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Table;

use DOMXPath;
use DOMDocument;
use Symfony\Component\VarDumper\VarDumper;

use Illuminate\Support\Facades\Validator;

class WordController extends Controller
{
    public function generateWordBackup()
    {

        $relativePath = 'images/document.docx';


        // Get the fully qualified path to the file
        $filePath = public_path($relativePath);

        // if (file_exists($filePath)) {
        //     // Provide a suggested file name for the download
        //     $suggestedFileName = 'downloaded_file.docx';

        //     // Return a download response
        //     return response()->download($filePath, $suggestedFileName);
        // } else {
        //     // File not found, return a 404 response or handle the error as needed
        //     return response()->json(['error' => 'File not found'], 404);
        // }

        // Check if the file exists
        if (file_exists($filePath)) {
            // Provide a suggested file name for the download
            $suggestedFileName = 'downloaded_file.docx';

            try {
                // Load the Word document
                $phpWord = IOFactory::load($filePath);


                // Check if $phpWord is an instance of PhpOffice\PhpWord\PhpWord
                if ($phpWord instanceof PhpOffice\PhpWord\PhpWord) {
                    // The file was loaded successfully
                    echo 'File loaded successfully!' . PHP_EOL;

                    // Access document properties (optional)
                    $properties = $phpWord->getDocInfo();
                    echo 'Title: ' . $properties->getTitle() . PHP_EOL;
                    echo 'Creator: ' . $properties->getCreator() . PHP_EOL;
                    echo 'Last modified by: ' . $properties->getLastModifiedBy() . PHP_EOL;
                    echo 'Created: ' . $properties->getCreated() . PHP_EOL;
                    echo 'Modified: ' . $properties->getModified() . PHP_EOL;

                    // Access document content (optional)
                    $sections = $phpWord->getSections();
                    foreach ($sections as $section) {
                        $elements = $section->getElements();
                        foreach ($elements as $element) {
                            // Process each element as needed
                            // For example, if $element is a \PhpOffice\PhpWord\Element\TextRun, you can access its text using $element->getText()
                        }
                    }
                } else {
                    echo 'Error: Unable to load the file.' . PHP_EOL;
                }
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage() . PHP_EOL;
            }

            // Return a download response
            // return response()->download($filePath, $suggestedFileName);
        } else {
            // File not found, return a 404 response or handle the error as needed
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function generateWord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
            ], 400);
        }

        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $document->move(public_path('images'), 'document.docx');

            // Create a new PHPWord object
            // $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $fileword = public_path('images/document.docx');

            $phpWord = \PhpOffice\PhpWord\IOFactory::load($fileword);

            // Check if $phpWord is an instance of PhpOffice\PhpWord\PhpWord
            if ($phpWord instanceof PhpOffice\PhpWord\PhpWord) {
                // The file was loaded successfully
                echo 'File loaded successfully!' . PHP_EOL;

                // Access document properties (optional)
                $properties = $phpWord->getDocInfo();
                echo 'Title: ' . $properties->getTitle() . PHP_EOL;
                echo 'Creator: ' . $properties->getCreator() . PHP_EOL;
                echo 'Last modified by: ' . $properties->getLastModifiedBy() . PHP_EOL;
                echo 'Created: ' . $properties->getCreated() . PHP_EOL;
                echo 'Modified: ' . $properties->getModified() . PHP_EOL;

                // Access document content (optional)
                $sections = $phpWord->getSections();
                foreach ($sections as $section) {
                    $elements = $section->getElements();
                    foreach ($elements as $element) {
                        // Process each element as needed
                        // For example, if $element is a \PhpOffice\PhpWord\Element\TextRun, you can access its text using $element->getText()
                    }
                }
            } else {
                echo 'Error: Unable to load the file.' . PHP_EOL;
            }


            $section = $phpWord->getSection(0);

            // Add header and footer
            $header = $section->addHeader();
            $footer = $section->addFooter();

            // Set header content with an image
            $imagePath = public_path('images/logo.png'); // Replace with the actual path to your image
            $header->addImage($imagePath, array('width' => 250, 'height' => 60));

            // Set footer content with HTML
            $htmlFooterContent = '
                <h1 style="font-size: 16px; color: #00395d;">
                YOUR WEBSITE HERE | YOUR NUMBER HERE | YOUR EMAIL HERE
                </h1>
                
                <p style="text-align: center; font-size: 10px;">This document and its content are protected by Canadian, U.S. and International copyright laws. Reproduction and distribution <br/> of this document and its content without the written permission of <strong>Your Company Name</strong> Here is strictly prohibited.
                </p>';
            \PhpOffice\PhpWord\Shared\Html::addHtml($footer, $htmlFooterContent, false, false);

            // Save the Word document
            $filename = 'document.docx';
            $path = public_path("uploads/{$filename}");
            // $phpWord->save($path);

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($path);

            // Download the Word document
            return response()->download($path, $filename)->deleteFileAfterSend(true);
        }
    }
}
