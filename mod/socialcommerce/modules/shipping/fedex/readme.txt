Algirdas Varnagiris
algirdas@varnagiris.net
www.varnagiris.net


FedEx Ship Manager (FSM) Direct was designed to allow customers to connect directly to the FedEx back end systems using their own communications protocol. Welcome to FSM Direct!
This solution is applicable only to those customers that have the development resources and knowledge to develop their own FedEx interface.

Before you begin:
FSM Direct Site Registration: To register in the FSM Direct program, go to http://www.fedex.com/us/solutions/wis/. Register and complete the brief registration form.

Step 1
Read and accept the FedEx Ship Manager Direct license agreement.
Step 2
Review the FedEx Direct Documentation: Determine if this alternative or FedEx
API is appropriate for your application.
Step 3
Develop your application.
Step 4
Send an email to websupport@fedex.com. Request to be set up for testing. Attach
FedEx Express and/or FedEx Ground account number(s).
Step 5
Test your application.


All documentation can be found here:
http://www.fedex.com/us/solutions/wis/pdf/fsm_directmanual.pdf
http://www.fedex.com/us/solutions/wis/pdf/xml_transguide.pdf

How to use this Fedex rates class:

    $fedex = new Fedex;
    $fedex->setServer("https://gatewaybeta.fedex.com/GatewayDC");
    $fedex->setAccountNumber(123123123);
    $fedex->setMeterNumber(12312312);
    $fedex->setCarrierCode("FDXE");
    $fedex->setDropoffType("REGULARPICKUP");
    $fedex->setService($service, $serviceName);
    $fedex->setPackaging("YOURPACKAGING");
    $fedex->setWeightUnits("LBS");
    $fedex->setWeight(17);
    $fedex->setOriginStateOrProvinceCode("OH");
    $fedex->setOriginPostalCode(44333);
    $fedex->setOriginCountryCode("US");
    $fedex->setDestStateOrProvinceCode("CA");
    $fedex->setDestPostalCode(90210);
    $fedex->setDestCountryCode("US");
    $fedex->setPayorType("SENDER");
    
    $price = $fedex->getPrice();

All available variables can be found here: http://www.fedex.com/us/solutions/wis/pdf/xml_transguide.pdf (FDXRateRequest)

Possible Fedex answer:

fedex Object
(
    [server] => https://gatewaybeta.fedex.com/GatewayDC
    [accountNumber] => 123123123
    [meterNumber] => 12312312
    [carrierCode] => FDXE
    [dropoffType] => REGULARPICKUP
    [service] => STANDARDOVERNIGHT
    [serviceName] => FedEx Standard Overnight
    [packaging] => YOURPACKAGING
    [weightUnits] => LBS
    [weight] => 17
    [originStateOrProvinceCode] => OH
    [originPostalCode] => 44333
    [originCountryCode] => US
    [destStateOrProvinceCode] => CA
    [destPostalCode] => 90210
    [destCountryCode] => US
    [payorType] => SENDER
    [price] => price Object
        (
            [service] => FedEx Standard Overnight
            [rate] => 86.37
            [response] => Array
                (
                  ...//Here is full xml response
                )
        )
)
