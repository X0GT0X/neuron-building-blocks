# Neuron Building Blocks library 
[![codecov](https://codecov.io/github/X0GT0X/neuron-building-blocks/branch/main/graph/badge.svg?token=SHIZD4WVB5)](https://codecov.io/github/X0GT0X/neuron-building-blocks)
[![CI](https://github.com/X0GT0X/neuron-building-blocks/actions/workflows/ci.yml/badge.svg)](https://github.com/X0GT0X/neuron-building-blocks/actions/workflows/ci.yml)

Internal Neuron library with Building blocks for microservices.

## Local development

### Requirements
1. PHP (^8.4)
2. Composer

### Available commands
- `make test` - unit testing
- `make php-cs` - PHP CS fixer
- `make php-stan` - PHP Stan
- `make php-stan-baseline` - PHP Stan baseline generation

### Library includes
- Domain events, business rules and Entity
- Domain event notifications and dispatching
- Inbox/Outbox messages/interfaces
- Integration events
- UI request param converter
