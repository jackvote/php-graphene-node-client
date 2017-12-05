<?php

namespace GrapheneNodeClient\Tools;


use GrapheneNodeClient\Commands\CommandQueryDataInterface;
use GrapheneNodeClient\Tools\ChainOperations\ChainOperations;
use GrapheneNodeClient\Tools\ChainOperations\OperationSerializer;
use StephenHill\Base58;
use TrafficCophp\ByteBuffer\Buffer;

class Auth
{
    /**
     * @param string $privateWif Private (posting key?) wif
     *
     * @return string outputs Private key as hex
     * @throws \Exception
     */
    public static function PrivateKeyFromWif($privateWif) {
//        var private_wif = new Buffer(base58.decode(_private_wif));
//        var version = private_wif.readUInt8(0);
//        assert.equal(0x80, version, `Expected version ${0x80}, instead got ${version}`);
//        // checksum includes the version
//        var private_key = private_wif.slice(0, -4);
//        var checksum = private_wif.slice(-4);
//        var new_checksum = hash.sha256(private_key);
//        new_checksum = hash.sha256(new_checksum);
//        new_checksum = new_checksum.slice(0, 4);
//        if (checksum.toString() !== new_checksum.toString())
//            throw new Error('Invalid WIF key (checksum miss-match)')
//
//        private_key = private_key.slice(1);
//return PrivateKey.fromBuffer(private_key);

        //checking wif version
        $base58 = new Base58();
        $wifBuffer = new Buffer();
        $wifBuffer->write($base58->decode($privateWif));
        $version = $wifBuffer->readInt8(0);
        if ($version !== 128) {
            //        assert.equal(0x80, version, `Expected version ${0x80}, instead got ${version}`);
            throw new \Exception('Expected version 128, instead got ' . $version);
        }

        //checking WIF checksum
        $private_key = $wifBuffer->read(0, $wifBuffer->length() - 4);
        $checksum = $wifBuffer->read($wifBuffer->length() - 4, 4);
        $new_checksum = hash('sha256', $private_key, true);
        $new_checksum = hash('sha256', $new_checksum, true);
        $new_checksum = substr($new_checksum, 0, 4);
        if ($new_checksum !== $checksum) {
            throw new \Exception('Invalid WIF key (checksum miss-match)');
        }

        //getting private_key
//        $private_key = substr(strrev($wifBuffer->read(0, $wifBuffer->length())), 1, -4);
        $private_key = substr($private_key, 1);
//        if (32 !== buf.length) {
//            console.log(`WARN: Expecting 32 bytes, instead got ${buf.length}, stack trace:`, new Error().stack);
//        }
        $length = strlen($private_key);
        if ($length !== 32) {
            throw new \Exception('Expecting 32 bytes for private_key, instead got ' . $length);
        }

        return $private_key;
    }


//    /**
//     * @param string $serializedSig binary string serialized signature
//     * @param string $skip skip the first byte with sing technical data (4 - compressed | 27 - compact)
//     *
//     * @return bool
//     */
//    public static function isSignatureCanonical($serializedSig, $skip)
//    {
//        //             test after secp256k1_ecdsa_recoverable_signature_serialize_compact
//        //        public static bool IsCanonical(byte[] sig, int skip)
//        //        {
//        //        return !((sig[skip + 0] & 0x80) > 0)
//        //        && !(sig[skip + 0] == 0 && !((sig[skip + 1] & 0x80) > 0))
//        //        && !((sig[skip + 32] & 0x80) > 0)
//        //        && !(sig[skip + 32] == 0 && !((sig[skip + 33] & 0x80) > 0));
//        //        }
//
//        $buffer = new Buffer();
//        $buffer->write($serializedSig);
//
//        return !(($buffer->readInt8($skip + 0, 1) & 0x80) > 0)
//            && !($buffer->readInt8($skip + 0, 1) === 0 && !(($buffer->readInt8($skip + 1, 1) & 0x80) > 0))
//            && !(($buffer->readInt8($skip + 32, 1) & 0x80) > 0)
//            && !($buffer->readInt8($skip + 32, 1) === 0 && !(($buffer->readInt8($skip + 33, 1) & 0x80) > 0));
//    }
}