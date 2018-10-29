<?php

namespace Spec\Minds\Core\Sessions;

use Minds\Core\Sessions\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Sessions\Repository;
use Minds\Core\Sessions\Session;
use Minds\Core\Config;
use Minds\Core;
use Minds\Common\Cookie;
use Zend\Diactoros\ServerRequest;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha512;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_build_a_session_with_request(
        Repository $repository,
        Config $config,
        Parser $jwtParser
    )
    {
        $this->beConstructedWith(
            $repository,
            $config,
            null,
            null,
            null
        );

        $config->get('sessions')
            ->shouldBeCalled()
            ->willReturn([
                'public_key' => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApvnChiEHxXmpMNaPTwdc
tkTDo9enXhHArO77yfLHZoB1J98B7GZ7GF+W19yM+kJKgJudEmLw22YW8Ycr5Aen
hl1JMhmGBpiY+XyMaPc4vWufrEfPUVpjOAVqx+OpRGOogJx29K0MkqUESITj4gVn
7BxKCOE7qNbXcYYAiTWot2ODIBZeNZokm/9zrZ95jjOiqP/CL9PN+mYc6WeRr4w5
EXCnkswu02Yzdtj5Xxyms+ur4iceOy9a6jE8kIqGfUPno/VdeJlnVMpV60QDkWtE
yA4hI7SirLQ6AZQtQyIt0LzVGRGg1u1iA/sRjGwB7dHWtc7JcG1rmp7xfVA7SznN
wwIDAQAB
-----END PUBLIC KEY-----",
            ]);

        $token = (new Builder)
                    //->issuedBy('spec.tests')
                    //->canOnlyBeUsedBy('spec.tests')
                    ->setId('mock_session_id', true)
                    ->setExpiration(time() + 3600) // 1 hour
                    ->set('user_guid', 'user_1')
                    ->sign(new Sha512, new Key("-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEApvnChiEHxXmpMNaPTwdctkTDo9enXhHArO77yfLHZoB1J98B
7GZ7GF+W19yM+kJKgJudEmLw22YW8Ycr5Aenhl1JMhmGBpiY+XyMaPc4vWufrEfP
UVpjOAVqx+OpRGOogJx29K0MkqUESITj4gVn7BxKCOE7qNbXcYYAiTWot2ODIBZe
NZokm/9zrZ95jjOiqP/CL9PN+mYc6WeRr4w5EXCnkswu02Yzdtj5Xxyms+ur4ice
Oy9a6jE8kIqGfUPno/VdeJlnVMpV60QDkWtEyA4hI7SirLQ6AZQtQyIt0LzVGRGg
1u1iA/sRjGwB7dHWtc7JcG1rmp7xfVA7SznNwwIDAQABAoIBACJ8DJek9LTtBmtG
tLwumhAurXUGEdPUuMU+ahPwJwxdVVTRstT+6UdEXqPgMeFxlW9wNAVbF8FIGU7y
ircCea+/TmGhcdOk6lsERP9cp4Q/WO+8uO1lTH6CZ+Y2d3vfVSqSpeKsZp9Wo0bS
4zmHwkm6IfQpiCe7jy0r7qpnwZt2BUoubT7COG86efFFPfgEdxkIpEPrk3KNSHLY
I96NzyAd3NOzHTYWgART87gjcdMhmWyLjzhZDn5X01kZY1yJMQmKO+6SzM6Mbku2
CdjsfdVDFlOxuU1IB9+tYxLkdz0epnFMEiWlsE5KL3P724pRFEO+NoRP1guan35x
I1jD04kCgYEA3C1jeWDFmm1NoknDDLxWcqZ2UlfAGJBGvEzzjJCyhE73vFH3Lv/H
Qd10Mw65jGrXg5ILbZV1CxDSvsx4fKXj3QUPs82M3/XlJB64J1NfUy5+UC/vcQ8q
tLKWne2q0SoLbLugZrm7RwKTXGT4YdTOGxCaBRUIvsy0uG/4EHb/ILUCgYEAwiR3
eic/ke26GVfsQGLUv+95/1owWzYrzcepD2CtTWCmVRW/Sa6EZ9CCaXHmRX4uV78O
vQmp6qIT4bVhG94iyY/WJPjAnrkI2ahNCV8olYM5115BNmaHeaARzXEgi2UyOESU
ZgTDFOWN9NpfHN6dljfRXpI8qlF8igV7sRAgV5cCgYAZX/3D4lxDtO8qkfexww7v
fbHLQaO48P/F+dRj0dVRHEy+3m9vcjkDpUMcE0ldHn8iAbXhdkUb9l9jb+s+6lt9
gHTT0w+2S/+RjxzII3qr+oLCORQOYqIYWzCymM6D9qWEbYdJ74Pe5jQXhOd/Vug+
BEbL6SWt36fATd83/o7etQKBgDP33P+W1/5xG1rDXVtS2U5ThV2kP8N6ubkI1Clo
oJtQ3tVxz9WiYJEFkJM3SQObJj6YxxI1LwW+wwGtMsRp7vfzh8g3yh/yufrBgXWb
wlpbWTVcZqpwQZ1+CqXqvWJzAUFsoii455uFYz2C4ujwclCOun3NOW4CCAtOMnEQ
NwgbAoGBALPXHBmbxlZuMdwfPBMbZIbLnUeksXkFjIwxb6yestZe5vyAjobjMC9/
hLom+msoew3BaAjwWGpOuSs3U96U+THoVIFyG+LF7tCck8PKpY3n4vKb7908WDUX
aTpdB3sjEe8ov+al2kJYBSJcqbUmUMVCY7v0Zig2VlYMPjzn/icP
-----END RSA PRIVATE KEY-----"))
                    ->getToken();

        $request = new ServerRequest();

        $request = $request->withCookieParams([
            'minds_sess' => $token,
        ]);

        $session = new Session();
        $session
            ->setId('mock_session_id')
            ->setUserGuid('user_1')
            ->setToken($token)
            ->setExpires(time() + 3600);

        $repository->get('user_1', 'mock_session_id')
            ->shouldBeCalled()
            ->willReturn($session);

        $this->withRouterRequest($request);

        // Confirm the session was set
        $this->getSession()->getId()
            ->shouldBe($session->getId());
    }

    function it_should_not_build_a_session_with_request_if_not_on_server(
        Repository $repository,
        Config $config,
        Parser $jwtParser
    )
    {
        $this->beConstructedWith(
            $repository,
            $config,
            null,
            null,
            null
        );

        $config->get('sessions')
            ->shouldBeCalled()
            ->willReturn([
                'public_key' => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApvnChiEHxXmpMNaPTwdc
tkTDo9enXhHArO77yfLHZoB1J98B7GZ7GF+W19yM+kJKgJudEmLw22YW8Ycr5Aen
hl1JMhmGBpiY+XyMaPc4vWufrEfPUVpjOAVqx+OpRGOogJx29K0MkqUESITj4gVn
7BxKCOE7qNbXcYYAiTWot2ODIBZeNZokm/9zrZ95jjOiqP/CL9PN+mYc6WeRr4w5
EXCnkswu02Yzdtj5Xxyms+ur4iceOy9a6jE8kIqGfUPno/VdeJlnVMpV60QDkWtE
yA4hI7SirLQ6AZQtQyIt0LzVGRGg1u1iA/sRjGwB7dHWtc7JcG1rmp7xfVA7SznN
wwIDAQAB
-----END PUBLIC KEY-----",
            ]);

        $token = (new Builder)
                    //->issuedBy('spec.tests')
                    //->canOnlyBeUsedBy('spec.tests')
                    ->setId('mock_session_id', true)
                    ->setExpiration(time() + 3600) // 1 hour
                    ->set('user_guid', 'user_1')
                    ->sign(new Sha512, new Key("-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEApvnChiEHxXmpMNaPTwdctkTDo9enXhHArO77yfLHZoB1J98B
7GZ7GF+W19yM+kJKgJudEmLw22YW8Ycr5Aenhl1JMhmGBpiY+XyMaPc4vWufrEfP
UVpjOAVqx+OpRGOogJx29K0MkqUESITj4gVn7BxKCOE7qNbXcYYAiTWot2ODIBZe
NZokm/9zrZ95jjOiqP/CL9PN+mYc6WeRr4w5EXCnkswu02Yzdtj5Xxyms+ur4ice
Oy9a6jE8kIqGfUPno/VdeJlnVMpV60QDkWtEyA4hI7SirLQ6AZQtQyIt0LzVGRGg
1u1iA/sRjGwB7dHWtc7JcG1rmp7xfVA7SznNwwIDAQABAoIBACJ8DJek9LTtBmtG
tLwumhAurXUGEdPUuMU+ahPwJwxdVVTRstT+6UdEXqPgMeFxlW9wNAVbF8FIGU7y
ircCea+/TmGhcdOk6lsERP9cp4Q/WO+8uO1lTH6CZ+Y2d3vfVSqSpeKsZp9Wo0bS
4zmHwkm6IfQpiCe7jy0r7qpnwZt2BUoubT7COG86efFFPfgEdxkIpEPrk3KNSHLY
I96NzyAd3NOzHTYWgART87gjcdMhmWyLjzhZDn5X01kZY1yJMQmKO+6SzM6Mbku2
CdjsfdVDFlOxuU1IB9+tYxLkdz0epnFMEiWlsE5KL3P724pRFEO+NoRP1guan35x
I1jD04kCgYEA3C1jeWDFmm1NoknDDLxWcqZ2UlfAGJBGvEzzjJCyhE73vFH3Lv/H
Qd10Mw65jGrXg5ILbZV1CxDSvsx4fKXj3QUPs82M3/XlJB64J1NfUy5+UC/vcQ8q
tLKWne2q0SoLbLugZrm7RwKTXGT4YdTOGxCaBRUIvsy0uG/4EHb/ILUCgYEAwiR3
eic/ke26GVfsQGLUv+95/1owWzYrzcepD2CtTWCmVRW/Sa6EZ9CCaXHmRX4uV78O
vQmp6qIT4bVhG94iyY/WJPjAnrkI2ahNCV8olYM5115BNmaHeaARzXEgi2UyOESU
ZgTDFOWN9NpfHN6dljfRXpI8qlF8igV7sRAgV5cCgYAZX/3D4lxDtO8qkfexww7v
fbHLQaO48P/F+dRj0dVRHEy+3m9vcjkDpUMcE0ldHn8iAbXhdkUb9l9jb+s+6lt9
gHTT0w+2S/+RjxzII3qr+oLCORQOYqIYWzCymM6D9qWEbYdJ74Pe5jQXhOd/Vug+
BEbL6SWt36fATd83/o7etQKBgDP33P+W1/5xG1rDXVtS2U5ThV2kP8N6ubkI1Clo
oJtQ3tVxz9WiYJEFkJM3SQObJj6YxxI1LwW+wwGtMsRp7vfzh8g3yh/yufrBgXWb
wlpbWTVcZqpwQZ1+CqXqvWJzAUFsoii455uFYz2C4ujwclCOun3NOW4CCAtOMnEQ
NwgbAoGBALPXHBmbxlZuMdwfPBMbZIbLnUeksXkFjIwxb6yestZe5vyAjobjMC9/
hLom+msoew3BaAjwWGpOuSs3U96U+THoVIFyG+LF7tCck8PKpY3n4vKb7908WDUX
aTpdB3sjEe8ov+al2kJYBSJcqbUmUMVCY7v0Zig2VlYMPjzn/icP
-----END RSA PRIVATE KEY-----"))
                    ->getToken();

        $request = new ServerRequest();

        $request = $request->withCookieParams([
            'minds_sess' => $token,
        ]);

        $session = new Session();
        $session
            ->setId('mock_session_id')
            ->setUserGuid('user_1')
            ->setToken($token)
            ->setExpires(time() + 3600);

        $repository->get('user_1', 'mock_session_id')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->withRouterRequest($request);

        // Confirm the session was set
        $this->getSession()->shouldBeNull();
    }

    function it_should_not_build_a_session_with_request_if_forged_token(
        Repository $repository,
        Config $config,
        Parser $jwtParser
    )
    {
        $this->beConstructedWith(
            $repository,
            $config,
            null,
            null,
            null
        );

        Core\Session::setUserByGuid(null);

        $config->get('sessions')
            ->shouldBeCalled()
            ->willReturn([
                'public_key' => "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApvnChiEHxXmpMNaPTwdc
tkTDo9enXhHArO77yfLHZoB1J98B7GZ7GF+W19yM+kJKgJudEmLw22YW8Ycr5Aen
hl1JMhmGBpiY+XyMaPc4vWufrEfPUVpjOAVqx+OpRGOogJx29K0MkqUESITj4gVn
7BxKCOE7qNbXcYYAiTWot2ODIBZeNZokm/9zrZ95jjOiqP/CL9PN+mYc6WeRr4w5
EXCnkswu02Yzdtj5Xxyms+ur4iceOy9a6jE8kIqGfUPno/VdeJlnVMpV60QDkWtE
yA4hI7SirLQ6AZQtQyIt0LzVGRGg1u1iA/sRjGwB7dHWtc7JcG1rmp7xfVA7SznN
wwIDAQAB
-----END PUBLIC KEY-----",
            ]);

        $token = (new Builder)
                    //->issuedBy('spec.tests')
                    //->canOnlyBeUsedBy('spec.tests')
                    ->setId('mock_session_id', true)
                    ->setExpiration(time() + 3600) // 1 hour
                    ->set('user_guid', 'user_1')
                    ->sign(new Sha512, new Key("-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA4Q2RymeNni7hxITMC5kU/YWhV5KiTFPJB6wUGIXljE3t7aRh
hVmU9x12+DEd0C+/UG2GVhWQclj/A3ZMqd4GJI3ZUu94kmVXAThRVaJSw+bgAhg5
TVrp3JN54M7tb4Q2JINhE3Oc1yr+qBUBr8YlRnx2pvxo+v31i5dP3dABgtoJ27z2
tn+6ng/MX5OuARgZqoeAWEDiVfPSLNNJYe9sffqrxh6Gtz4q6Lh362s+0F4C3M3V
wiuz9mVLYDGdSYFClaTiY6koUZU7Q5QOghs0Y2PiD+s71k8lyQDFPJPuFWzaz0uF
JNyk63wxc8bTaWhyDW+aqr49ZjEpLQenAhvmYwIDAQABAoIBABLSWLQNzwlAsH8I
ACykI95EE8dIKyypATIUESYv5I/EYLa0PbeyU0QLgcymBvFHXP+z+T0v1oHJsaIv
fZ/jQq5B8r/96R6NPSaL+DlqNvPNVkS2k+xFQzPAXbwQckkWTJTnK/I9hAt2vA+A
mkCCGNH5TM8QYxNCtAt1nydU4xt3Sp+hZ/ddeO7zbBl+mdhop9+pqKtKi7Rz2bmt
R0s7CHrsXXLdeebEwIlKkmL2/2aUgS5icpdIxq3UEXC+T8F0kIZ0gYBF8+Nfr/bK
pugApTzdCHzcttpvtc59yrmDuP7g+jM3CTgQgzQid/yypC2flL8674HJZuSfGPj8
qYJPW2kCgYEA8NwIH1zKWlBmw58faLCM3pbdKqPr0bXciXgCtBRu1ygueU3hwuxi
sWokxIJ5/8TIxNNJnyFPG/zcosjzx+fZlzFMuWh520mN7UjtBjQ1MfmygvZNiPlx
U/xHUmekjJGggkrX7YSMfWtw2pyH1mnjWeaARgJBrIeffIz/MVwaAK8CgYEA7zMt
CWJUKlZUxdMmoNATpe1XRksjdmZJ5+e5mtZKjpyE28VmiBb1ed22swLeu7m12tZL
4tlQJeN0DF2GBNiyFgXO8AJbPpVuCzZ0goFj63S/7Xegm5iCE3XGbEdMlXiu6rNh
fxm3dQzJhg25OcxLNRKJ8UdvIRlTnrPakgA0Wo0CgYARKq2NhrCJdVeNs8aqUIMJ
zljfFCXXoY2hYqNWsGoh/aQqUym3GeAC4xzRwLbvSthAZwrFQ7t+tCwJkicF5Xl1
tDbStCaTQY/TDLKQEaKE+FYHzYRDMfwozz9KGUb6GdsFzk3LiPn2anT1r9hiqNNi
cfJMixahNF1ipC4AF59m0wKBgQDt0B51lvoAwaY0rBJajaULMaW0cF3IiDfwRXVF
mMm1ca2xZlfi85iI2cKbxT22vBMuiCIYXmAN2050QMNOrDaaKJouCtm2phArO6+o
VIYIASIUMPxP1dd1C4IEAvsEHGEjXMaQ+/tmdxkgmyJNcWxQTqcwaAn3iVuWjE/B
GkzyvQKBgCKt/L78CQfcD7C/s3AqT5TvcQOKhXaSx9kdoETJ+U3CYQmfOhk65VVH
04pG6UqzqHxC+4yQ8IDt3/op+eplbfDFHKWuePNaGT8Kcoivzb9FG0ZBVCPVs1tm
YSoKTsWFlvr9YG4o6R2ktgzKJ5ofiGTz5e2wLzP3a0ma8vGNke4Q
-----END RSA PRIVATE KEY-----"))
                    ->getToken();

        $request = new ServerRequest();

        $request = $request->withCookieParams([
            'minds_sess' => $token,
        ]);

        $this->withRouterRequest($request);

        // Confirm the session was set
        $this->getSession()->shouldBeNull();
    }

    function it_should_save_session_to_client_and_server(
        Repository $repository,
        Cookie $cookie
    )
    {
        $this->beConstructedWith(
            $repository,
            null,
            $cookie
        );

        $session = new Session();
        $session
            ->setId('mock_session_id')
            ->setUserGuid('user_1')
            ->setToken('token')
            ->setExpires(time() + 3600);
        
        $this->setSession($session);

        $repository->add($session);

        $cookie->setName('minds_sess')
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setValue('token')
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setExpire(time() + 3600)
            ->shouldBeCalled()
            ->willReturn($cookie);
        
        $cookie->setSecure(true)
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setHttpOnly(true)
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setPath('/')
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->create()
            ->shouldBeCalled();

        $this->save();
    }

    function it_should_destroy_session_on_client_and_server(
        Repository $repository,
        Cookie $cookie
    )
    {
        $this->beConstructedWith(
            $repository,
            null,
            $cookie
        );

        $session = new Session();
        $session
            ->setId('mock_session_id')
            ->setUserGuid('user_1')
            ->setToken('token')
            ->setExpires(time() + 3600);
        
        $this->setSession($session);

        $repository->delete('mock_session_id');

        $cookie->setName('minds_sess')
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setValue('')
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setExpire(time() - 3600)
            ->shouldBeCalled()
            ->willReturn($cookie);
        
        $cookie->setSecure(true)
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setHttpOnly(true)
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->setPath('/')
            ->shouldBeCalled()
            ->willReturn($cookie);

        $cookie->create()
            ->shouldBeCalled();

        $this->destroy();
    }

}
