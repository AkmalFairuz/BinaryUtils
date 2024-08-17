<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\utils;

use AkmalFairuz\ByteBuf\ByteBuf;
use function round;

class BinaryStream{
	private ByteBuf $byteBuf;

	public function __construct(string $buffer = "", int $offset = 0){
		if($buffer === ""){
			$this->byteBuf = ByteBuf::alloc(32);
		}else{
			$this->byteBuf = ByteBuf::fromString($buffer);
		}
		$this->byteBuf->setOffset($offset);
	}

	public function __get(string $name) : mixed{
		return match($name){
			"buffer" => $this->byteBuf->toString(),
			"offset" => $this->byteBuf->getOffset(),
			default => throw new \Error("Undefined property: " . static::class . "::$name")
		};
	}

	public function __set(string $name, mixed $value) : void{
		if($name === "buffer"){
			if(!is_string($value)){
				throw new \TypeError("Property " . static::class . "::$name expects string, " . gettype($value) . " given");
			}
			$this->byteBuf = ByteBuf::fromString($value);
		}elseif($name === "offset"){
			if(!is_int($value)){
				throw new \TypeError("Property " . static::class . "::$name expects int, " . gettype($value) . " given");
			}
			$this->byteBuf->setOffset($value);
		}else{
			throw new \Error("Undefined property: " . static::class . "::$name");
		}
	}

	/**
	 * Rewinds the stream pointer to the start.
	 */
	public function rewind() : void{
		$this->byteBuf->setOffset(0);
	}

	public function setOffset(int $offset) : void{
		$this->byteBuf->setOffset($offset);
	}

	public function getOffset() : int{
		return $this->byteBuf->getOffset();
	}

	public function getBuffer() : string{
		return $this->byteBuf->toString();
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException if there are not enough bytes left in the buffer
	 */
	public function get(int $len) : string{
		return $this->byteBuf->read($len);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getRemaining() : string{
		return $this->byteBuf->remaining();
	}

	public function put(string $str) : void{
		$this->byteBuf->write($str);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getBool() : bool{
		return $this->byteBuf->read(1) !== "\x00";
	}

	public function putBool(bool $v) : void{
		$this->byteBuf->write($v ? "\x01" : "\x00");
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getByte() : int{
		return $this->byteBuf->readUnsignedByte();
	}

	public function putByte(int $v) : void{
		$this->byteBuf->writeByte($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getShort() : int{
		return $this->byteBuf->readUnsignedShort();
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getSignedShort() : int{
		return $this->byteBuf->readShort();
	}

	public function putShort(int $v) : void{
		$this->byteBuf->writeShort($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLShort() : int{
		return $this->byteBuf->readLUnsignedShort();
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getSignedLShort() : int{
		return $this->byteBuf->readLShort();
	}

	public function putLShort(int $v) : void{
		$this->byteBuf->writeLShort($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getTriad() : int{
		return $this->byteBuf->readTriad();
	}

	public function putTriad(int $v) : void{
		$this->byteBuf->writeTriad($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLTriad() : int{
		return $this->byteBuf->readLTriad();
	}

	public function putLTriad(int $v) : void{
		$this->byteBuf->writeLTriad($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getInt() : int{
		return $this->byteBuf->readInt();
	}

	public function putInt(int $v) : void{
		$this->byteBuf->writeInt($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLInt() : int{
		return $this->byteBuf->readLInt();
	}

	public function putLInt(int $v) : void{
		$this->byteBuf->writeLInt($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getFloat() : float{
		return $this->byteBuf->readFloat();
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getRoundedFloat(int $accuracy) : float{
		return round($this->byteBuf->readFloat(), $accuracy);
	}

	public function putFloat(float $v) : void{
		$this->byteBuf->writeFloat($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLFloat() : float{
		return $this->byteBuf->readLFloat();
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getRoundedLFloat(int $accuracy) : float{
		return round($this->byteBuf->readLFloat(), $accuracy);
	}

	public function putLFloat(float $v) : void{
		$this->byteBuf->writeLFloat($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getDouble() : float{
		return $this->byteBuf->readDouble();
	}

	public function putDouble(float $v) : void{
		$this->byteBuf->writeDouble($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLDouble() : float{
		return $this->byteBuf->readLDouble();
	}

	public function putLDouble(float $v) : void{
		$this->byteBuf->writeLDouble($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLong() : int{
		return $this->byteBuf->readLong();
	}

	public function putLong(int $v) : void{
		$this->byteBuf->writeLong($v);
	}

	/**
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getLLong() : int{
		return $this->byteBuf->readLLong();
	}

	public function putLLong(int $v) : void{
		$this->byteBuf->writeLLong($v);
	}

	/**
	 * Reads a 32-bit variable-length unsigned integer from the buffer and returns it.
	 *
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getUnsignedVarInt() : int{
		return $this->byteBuf->readUnsignedVarInt();
	}

	/**
	 * Writes a 32-bit variable-length unsigned integer to the end of the buffer.
	 */
	public function putUnsignedVarInt(int $v) : void{
		$this->byteBuf->writeUnsignedVarInt($v);
	}

	/**
	 * Reads a 32-bit zigzag-encoded variable-length integer from the buffer and returns it.
	 *
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getVarInt() : int{
		return $this->byteBuf->readVarInt();
	}

	/**
	 * Writes a 32-bit zigzag-encoded variable-length integer to the end of the buffer.
	 */
	public function putVarInt(int $v) : void{
		$this->byteBuf->writeVarInt($v);
	}

	/**
	 * Reads a 64-bit variable-length integer from the buffer and returns it.
	 *
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getUnsignedVarLong() : int{
		return $this->byteBuf->readUnsignedVarLong();
	}

	/**
	 * Writes a 64-bit variable-length integer to the end of the buffer.
	 */
	public function putUnsignedVarLong(int $v) : void{
		$this->byteBuf->writeUnsignedVarLong($v);
	}

	/**
	 * Reads a 64-bit zigzag-encoded variable-length integer from the buffer and returns it.
	 *
	 * @phpstan-impure
	 * @throws BinaryDataException
	 */
	public function getVarLong() : int{
		return $this->byteBuf->readVarLong();
	}

	/**
	 * Writes a 64-bit zigzag-encoded variable-length integer to the end of the buffer.
	 */
	public function putVarLong(int $v) : void{
		$this->byteBuf->writeVarLong($v);
	}

	/**
	 * Returns whether the offset has reached the end of the buffer.
	 */
	public function feof() : bool{
		return $this->byteBuf->getOffset() >= $this->byteBuf->getUsedBufferLength();
	}
}
