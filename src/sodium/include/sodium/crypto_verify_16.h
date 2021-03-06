#ifndef crypto_verify_16_H
#define crypto_verify_16_H

#include "export.h"

#define crypto_verify_16_BYTES 16U

#ifdef __cplusplus
extern "C" {
#endif

SODIUM_EXPORT
int crypto_verify_16(const unsigned char *x, const unsigned char *y);

#define crypto_verify_16_ref crypto_verify_16

#ifdef __cplusplus
}
#endif

#endif
