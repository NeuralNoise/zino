#include "sanitizer.h"

int main( int argc, char **argv ) {
    TidyBuffer output = { 0 };
    TidyBuffer errbuf = { 0 };
    int rc = -1;
    Bool ok;
    string s;

    copy( istreambuf_iterator< char >( cin ), istreambuf_iterator< char >(), back_inserter( s ) );
    
    const char* input = s.c_str();
    
    TidyDoc tdoc = tidyCreate(); // initialize

    tidyOptSetInt( tdoc, TidyIndentContent, 0 );
    tidyOptSetInt( tdoc, TidyWrapLen, 0 );
    tidyOptSetValue( tdoc, TidyAltText, "" );
    tidyOptSetValue( tdoc, TidyCharEncoding, "utf8" );
    tidyOptSetBool( tdoc, TidyXhtmlOut, yes );
    tidyOptSetBool( tdoc, TidyHideComments, yes );
    tidyOptSetInt( tdoc, TidyBodyOnly, yes );
    tidyOptSetBool( tdoc, TidyMakeClean, yes );
    tidyOptSetBool( tdoc, TidyLogicalEmphasis, yes );
    tidyOptSetBool( tdoc, TidyDropPropAttrs, yes );
    tidyOptSetBool( tdoc, TidyDropFontTags, yes );
    tidyOptSetBool( tdoc, TidyDropEmptyParas, yes );
    tidyOptSetBool( tdoc, TidyQuoteMarks, yes );
    tidyOptSetBool( tdoc, TidyQuoteAmpersand, yes );
    tidyOptSetBool( tdoc, TidyForceOutput, yes );
    tidyOptSetBool( tdoc, TidyEscapeCdata, yes );
    tidyOptSetBool( tdoc, TidyJoinClasses, yes );
    // tidyOptSetBool( tdoc, TidyOutputBOM, no );

    rc = tidySetErrorBuffer( tdoc, &errbuf ); // Capture diagnostics
    
    if ( rc >= 0 ) {
        rc = tidyParseString( tdoc, input ); // Parse the input
    }
    if ( rc >= 0 ) {
        rc = tidyCleanAndRepair( tdoc ); // Tidy it up!
    }
    if ( rc >= 0 ) {
        rc = tidyRunDiagnostics( tdoc );
    }
    if ( rc >= 0 ) {
        rc = tidySaveBuffer( tdoc, &output );
    }
    if ( rc >= 0 ) {
        if ( rc > 0 ) {
            cerr << "\nDiagnostics:\n\n" << errbuf.bp;
            cout << output.bp;
        }
        else {
            cerr << "A severe error occurred:\n" << rc;
        }
    }
    
    tidyBufFree( &output );
    tidyBufFree( &errbuf );
    tidyRelease( tdoc );
    
    return rc;
}

