#!/usr/bin/env python
import sys
import socket
import struct
from time import sleep

UDP_IP = "192.168.100.100"
UDP_PORT = 8899
sock = None

def send_udp_message(p1, p2, p3):
    hexstring = struct.pack('B', p1) + struct.pack('B', p2) + struct.pack('B', p3)
    print ("Sent UDP command " + str(hex(p1)) + " " + str(hex(p2)) + " " + str(hex(p3)) + " to " + UDP_IP + ":" + str(UDP_PORT))
    sock.sendto(hexstring, (UDP_IP, UDP_PORT))
    return

def printAbout():
	print "Usage: milight ZONE COMMAND [PARAMETER]"
	print ""
	print "The ZONE argument specifies which bulb zone the command refers to."
	print "Possible values:"
	print "  ALL/0 - All zones"
	print "  1     - Zone 1"
	print "  2     - Zone 2"
	print "  3     - Zone 3"
	print "  4     - Zone 4"
	print ""
	print "The COMMAND argument specifies the command to be sent to the given bulb zone."
	print "Some commands require a parameter (see below)."
	print "Accepted commands:"
	print "  ON                 - Turn the bulbs in the given zone on."
	print "  OFF                - Turn the bulbs in the given zone off."
	print "  WHITE/W            - Set the color of the bulbs in the given zone to white."
	print "  DISCO/D [+/-]      - If no parameter is specified, turn disco mode on."
	print "                       The '+' optional parameter increases the disco speed."
	print "                       The '-' optional parameter decreases the disco speed."
	print "  BRIGHTNESS/B VALUE - Set the brightness of the bulbs in the given zone."
	print "                       The VALUE mandatory parameter specifies the brightness"
	print "                       and must be an integer number in the range 1-19."
	print "  COLOR/C VALUE      - Set the color of the bulbs in the given zone."
	print "                       The VALUE mandatory parameter specifies the color"
	print "                       and must be an integer number in the range 0-255."
	return

def sendOnCommand(zone):
    codes = [66, 69, 71, 73, 75];
    send_udp_message(codes[int(zone)], 00, 85);
    return

def sendOffCommand(zone):
    codes = [65, 70, 72, 74, 76];
    send_udp_message(codes[int(zone)], 00, 85);
    return

def sendWhiteCommand(zone):
    codes = [194, 197, 199, 201, 203];
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(codes[int(zone)], 00, 85);
    return

def senColorCommand (zone, color):
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(64, color, 85);
    return

def sendBrightnessCommand(zone, brightness):
    codes = [2, 3, 4, 5, 8, 9, 10, 11, 13, 14, 15, 16, 18, 19, 20, 21, 23, 24, 25]
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(78, codes[int(brightness)-1], 85)
    return

def sendDiscoCommand (zone):
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(77, 00, 85)
    print "sendDiscoCommand"
    return

def sendDiscoIncCommand (zone):
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(68, 00, 85)
    return

def sendDiscoDecCommand (zone):
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(67, 00, 85)
    return

def processCommand(programName, zone, command, param):
    if command == "ON":
        if param != "":
            printAbout()
            return False
        return sendOnCommand(zone)

    if command == "OFF":
        if param != "":
            printAbout()
            return False
        return sendOffCommand(zone)

    if command == "WHITE" or command == "W":
        if param != "":
            printAbout()
            return False
        return sendWhiteCommand(zone)

    if command == "COLOR" or command == "C":
        color = int(param)
        if param == "" or color < 0 or color > 255:
            printAbout()
            return False
        return senColorCommand (zone, color)

    if command == "BRIGHTNESS" or command == "B":
        brightness = int(param)
        if param == "" or int(brightness) < 1 or int(brightness) > 19:
            printAbout()
            return False
        return sendBrightnessCommand(zone, brightness)

    if command == "DISCO" or command == "D":
        if param == "":
            return sendDiscoCommand(zone)
        if param == "+":
            return sendDiscoIncCommand (zone);
        if param == "-":
            return sendDiscoDecCommand (zone)
        printAbout()
        return False

    printAbout();
    return False

# parse command line options
if len(sys.argv) < 3 or len(sys.argv)>4 :
    printAbout()
    exit(1)

programName = str(sys.argv[0])
zone        = str(sys.argv[1]).upper()
command     = str(sys.argv[2]).upper()
param       = ""

if len(sys.argv) > 3 :
    param = str(sys.argv[3]).upper()

# Connect to inet UDP
sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)

result = processCommand(programName, zone, command, param)

exit(0 if result else 1);